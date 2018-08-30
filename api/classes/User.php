<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author McFarlane.a
 */
class User {

    //put your code here
    private $id;
    private $name;
    private $email;
    private $username;
    private $password;
    private $project_admin;
    private $department_owner;
    private $ElggObj;
    private $emailDomains   = array('forces.gc.ca', 'canada.ca', 'tpsgc-pwgsc.ca');
    private $userCollection = array();
    public $session;

    public function __construct($id = null, $session = null) {
        if ($id) {
            $this->ElggObj          = get_entity($id);
            $this->id               = $this->ElggObj->guid;
            $this->name             = $this->ElggObj->name;
            $this->username         = $this->ElggObj->username;
            $this->email            = $this->ElggObj->email;
            $this->validated        = $this->ElggObj->validated;
            $this->deactivated      = $this->ElggObj->deactivated;
            $this->project_admin    = $this->ElggObj->project_admin;
            $this->project_types    = $this->ElggObj->project_types;
            $this->department_owner = $this->ElggObj->department_owner;
            $this->last_action      = $this->ElggObj->last_action;
        }
        $this->session = $session;
    }

    public static function newUser($payload = array(), $session) {
        $instance = new self(null, $session);

        foreach ($payload as $k => $v) {
            $instance->$k = $v;
        }

        return $instance;
    }

    public function validate($payload = array()) {
        //make sure it is a valid field
        foreach ($payload as $k => $v) {
            if (!property_exists($this, $k)) {
                return false;
            }
        }

        //validate email
        if (isset($this->email)) {
            $domain = array_pop(explode('@', $this->email));

            if (!in_array($domain, $this->emailDomains)) {
                $e                       = new RegistrationException(elgg_echo('register:emailRules'));
                $this->session->errors[] = $e->getMessage();
                return false;
            }
        } else {
            $this->session->errors[] = "Email is a required field";
            return false;
        }

        //validate password
        if (strlen($this->password) < 8 || preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/', $this->password) == 0) {
            $e                       = new RegistrationException(elgg_echo('register:pswdInvld'));
            $this->session->errors[] = $e->getMessage();
            return false;
        }

        return true;
    }

    public function create() {
        //user registration
        try {
            $this->id = register_user($this->username, $this->password, $this->name, $this->email, false);
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }

    public function update($payload) {

        foreach ($payload as $key => $value) {
            if ($key == 'public_key') {

            } elseif ($key == 'project_admin') {
                $this->ElggObj->$key = $value ? '1' : null;
            } elseif ($key == 'validated') {
                return $this->validateUser();
            } elseif ($key == 'deactivated' || $key == 'activated') {
                $this->setStatus($value);
            } elseif ($key == 'project_types' || $key == 'department_owner') {
                $this->ElggObj->$key = ($value != null ? json_encode($value) : null);
            } else if($key == 'password') {
                $newPassword = md5("changeme" . date("Y") . $this->ElggObj->salt);
                $this->ElggObj->$key = $newPassword;
            } else {
                $this->ElggObj->$key = $value;
            }
        }

        if ($this->ElggObj->save()) {
            return true;
        }
        return false;
    }

    /**
     * $value will be TRUE for setting accounts to activated and FALSE for setting deactivated
     */
    private function setStatus($value) {
        if ($value == true) {
            $this->ElggObj->deleteMetadata('deactivated');
        } else {
            $this->ElggObj->deactivated = true;
        }
    }

    public function disable() {
        //disable user
        try {
            if (empty($this->ElggObj)) {
                $this->setElggObject();
            }

            $this->ElggObj->disable('uservalidationbyadmin_new_user', FALSE);

            // set user as unvalidated and send out validation email
            elgg_set_user_validation_status($this->id, FALSE);

            $this->notify();

            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }

    private function setElggObject() {
        $this->ElggObj = get_entity($this->id);
    }

    public function getDatatableData($sort = \null, $search = \null, $start = 0, $length = 10) {
        if ($sort) {
            $sort = "order by " . $sort;
        }

        if ($search) {
            $search = "(u.name LIKE '%" . sanitize_string($search) . "%' OR u.email LIKE '%" . sanitize_string($search) . "%' OR u.username LIKE '%" . sanitize_string($search) . "%')";
        }

        $query = "SELECT u.*, " .
                "(select " .
                "(case when msa2.string = '1' then 'yes' else 'no' end) as active " .
                "from elgg_metadata as mda " .
                "left join elgg_metastrings as msa on (msa.id=mda.name_id) " .
                "left join elgg_metastrings as msa2 on (msa2.id=mda.value_id) " .
                "where mda.entity_guid=u.guid and msa.string='validated' " .
                ") as active,  " .
                "(select " .
                "(case when msd2.string = '1' then 'yes' else 'no' end) as deactivated " .
                "from elgg_metadata as mdd " .
                "left join elgg_metastrings as msd on (msd.id=mdd.name_id) " .
                "left join elgg_metastrings as msd2 on (msd2.id=mdd.value_id) " .
                "where mdd.entity_guid=u.guid and msd.string='deactivated' " .
                ") as deactivated " .
                "from elgg_users_entity u ";

        $query .= ($search ? "where $search" : "");
        //hardcoded 10000000 because LIMIT expects a limit when combined with an offset
        $query .= " " . sanitize_string($sort) . " LIMIT " . sanitize_string($start) . ",100000000;";
        //$query .= " " . sanitize_string($sort) . " LIMIT " . sanitize_string($start) . "," . sanitize_string($length) . ";";


        $query3 = "SELECT DISTINCT e.* FROM elgg_entities e WHERE ((e.type = 'user')) AND e.site_guid = 1;";

        $result = array("limit" => get_data($query), "total" => get_data($query3));

        return $result;
    }

    //Alternative to the setCollection() function for improved performance
    public function setUserData() {
        $query = "SELECT u.*, " .
                "(select " .
                "(case when msa2.string = '1' then 'yes' else 'no' end) as active " .
                "from elgg_metadata as mda " .
                "left join elgg_metastrings as msa on (msa.id=mda.name_id) " .
                "left join elgg_metastrings as msa2 on (msa2.id=mda.value_id) " .
                "where mda.entity_guid=u.guid and msa.string='validated' " .
                ") as active, " .
                "(select " .
                "(case when msd2.string = '1' then 'yes' else 'no' end) as deactivated " .
                "from elgg_metadata as mdd " .
                "left join elgg_metastrings as msd on (msd.id=mdd.name_id) " .
                "left join elgg_metastrings as msd2 on (msd2.id=mdd.value_id) " .
                "where mdd.entity_guid=u.guid and msd.string='deactivated' " .
                ") as deactivated, " .
                "(select " .
                "(case when msa2.string = '1' then 'yes' else 'no' end) as project_admin " .
                "from elgg_metadata as mda " .
                "left join elgg_metastrings as msa on (msa.id=mda.name_id) " .
                "left join elgg_metastrings as msa2 on (msa2.id=mda.value_id) " .
                "where mda.entity_guid=u.guid and msa.string='project_admin' " .
                ") as project_admin, " .
                "(select msd2.string " .
                "from elgg_metadata as mdd " .
                "left join elgg_metastrings as msd on (msd.id=mdd.name_id) " .
                "left join elgg_metastrings as msd2 on (msd2.id=mdd.value_id) " .
                "where mdd.entity_guid=u.guid and msd.string='department_owner' " .
                ") as department_owner " .
                "from elgg_users_entity u ";

        $this->collection = get_data($query);
    }

    public function setCollection($params) {
        $options = array(
            "types"                     => "user",
            "limit"                     => false,
            "full_view"                 => false,
            "order_by"                  => "time_created DESC",
            "metadata_name_value_pairs" => array()
        );

        foreach ($params as $key => $param) {
            if ($key != 'public_key' || $key != 'project_types' || $key != 'department_owner') {
                $options["metadata_name_value_pairs"][] = array(
                    "name"  => $key,
                    "value" => $param
                );
            }
        }

        $users = elgg_get_entities_from_metadata($options);

        $this->collection = $users;
    }

    public function getCollection() {
        $return = array();

        foreach ($this->collection as $user) {
            $arr             = array();
            $arr['id']       = $user->guid;
            $arr['name']     = $user->name;
            $arr['username'] = $user->username;
            $arr['email']    = $user->email;

            $arr['validated'] = true;
            if ($user->validated == 0 && $user->validated != null) {
                $arr['validated'] = false;
            }

            $arr['deactivated'] = false;
            if ($user->deactivated) {
                $arr['deactivated'] = true;
            }

            // Create an array containing all the departments the user is part of
            $replace                 = array('[', ']', '"', '\\');
            $deptString              = str_replace($replace, '', $user->department_owner);
            $deptString              = explode(",", $deptString);
            $arr['department_owner'] = $deptString;

            $return[] = $arr;
        }

        return $return;
    }

    public function filterCollection($field, $value) {
        foreach ($this->collection as $key => $user) {
            if ($user->$field) {
                if (!in_array($value, json_decode($user->$field))) {
                    unset($this->collection[$key]);
                }
            } else {
                unset($this->collection[$key]);
            }
        }
    }

    private function setId($id) {
        $this->id = $id;
    }

    private function setName($name) {
        $this->name = $name;
    }

    public function setUser() {
        $this->id            = $this->ElggObj->guid;
        $this->name          = $this->ElggObj->name;
        $this->username      = $this->ElggObj->username;
        $this->email         = $this->ElggObj->email;
        $this->validated     = $this->ElggObj->validated;
        $this->deactivated   = $this->ElggObj->deactivated;
        $this->project_admin = $this->ElggObj->project_admin;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAll() {
        $arr              = array();
        $arr['id']        = $this->id;
        $arr['name']      = $this->name;
        $arr['username']  = $this->username;
        $arr['email']     = $this->email;
        $arr['validated'] = true;
        if ($this->validated == 0 && $this->validated != null) {
            $arr['validated'] = false;
        }
        $arr['deactivated'] = true;
        if ($this->deactivated) {
            $arr['deactivated'] = false;
        }
        $arr['last_action']      = $this->last_action;
        $arr['project_admin']    = $this->project_admin;
        $arr['project_types']    = $this->project_types;
        $arr["department_owner"] = $this->department_owner;

        return $arr;
    }

    public function validateUser() {
        $result = elgg_set_user_validation_status($this->id, TRUE, 'manual');

        return $result;
    }

    public function notify() {
        // create the activation link
        $code = uservalidationbyadmin_generate_code($this->id, $this->email);

        $validate = elgg_view('output/confirmlink', array(
            'confirm' => elgg_echo('uservalidationbyadmin:confirm_validate_user', array($this->username)),
            'href'    => "uservalidationbyadmin/validate?user_guids[]=$this->id&code=$code",
            'text'    => elgg_echo('uservalidationbyadmin:admin:validate')
        ));

        $validateLink = array_pop(explode('href = ', $validate));
        $index        = (strpos($validateLink, 'rel = ') - 3);
        $validateLink = substr($validateLink, 1, $index);

        //draft the email
        $subject = elgg_echo('uservalidationbyadmin:email:validate:header');
        $body    = elgg_echo('uservalidationbyadmin:email:validate:body');
        $body    .= "\r\n" . $validateLink;

        $notification = new Notification($this->id, $subject, $body);
        $notification->send();

       // Disable email to CDA LABS
       // $notification = new Notification(45602, $subject, $body);
       // $notification->send();
    }

}
