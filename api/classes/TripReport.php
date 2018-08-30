<?php

class TripReport extends ElggObject{
    public $session;
    
    public function __construct($session, $id) {
        parent::initializeAttributes();
        if($id) {
            parent::__construct($id);
        }
        
        $this->session = $session;
    }
    
    public static function newObj($payload, $session) {
        $instance = new self($session);
        $instance->setObj($payload);
        return $instance;
    }
    
    public static function withId($id, $session) {
        $instance = new self($session, $id);
        return $instance;
    }
    
    private function setObj($payload) {
        $this->attributes['subtype'] = 'trip_report';
        $this->title = $payload['title'];
        $this->travellers = (string) json_encode($payload['travellers']);
        $this->location = (string) $payload['location'];
        $this->start_date = $payload['start_date'];
        $this->end_date = $payload['end_date'];
        $this->opi = json_encode($payload['opi']);
        $this->attendees = $payload['attendees'];
        $this->purpose = $payload['purpose'];
        $this->discussions = json_encode($payload['discussions']);
        $issuesArr = $this->filterArr($payload['issues_of_concern']);
        $this->issues_of_concern = json_encode($issuesArr);
        //$this->internal_to_sc = json_encode($payload['internal_to_sc']);
        //$this->external_to_sc = json_encode($payload['external_to_sc']);
        $this->annex = json_encode($payload['annex']);
        $this->access_id = ACCESS_PUBLIC;
    }
    
    public function validate() {
        $flag = true;
        
        if(!$this->canEdit()) {
            $this->session->errors['permissions'] = elgg_echo('trip_reports:aclError');
        }
        if(is_null($this->title) || empty($this->title)){
            $this->session->errors['title'] = elgg_echo('trip_reports:noTitle');
            $flag = false;
        }
        if(is_null($this->start_date)){
            $this->session->errors['start_date'] = elgg_echo('trip_reports:noStartDate');
            $flag = false;
        }
        if(is_null($this->travellers)){
            $this->session->errors['travellers'] = elgg_echo('trip_reports:noTravellers');
            $flag = false;
        }

        return $flag;
    }
    
    public function create() {
        try{
            $this->save();
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function update($payload) {
        //apply update to object
        foreach($payload as $key=>$value) {
            $decoded = array("travellers","opi","discussions","issues_of_concern","internal_to_sc","external_to_sc","annex", "ratings");
            if(in_array($key, $decoded)){
                $value = json_encode($value);
            }
            
            $this->$key = $value;
        }
        
        //validate
        if(!$this->validate()){return false;}
        
        //save to persistent data store
        try{
            $this->save();
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function addRating($rating) {
        $ratings = json_decode($this->ratings);
        $user = $this->session->getUser();
        
        foreach($ratings as $index => $obj) {
            if($obj->user == $user->guid) {
                array_splice($ratings, $index, 1);
            }
        }
        
        $ratings[] = $rating;
        $encodedRatings = json_encode($ratings);
        
        $this->ratings = $encodedRatings;
        
        //save to persistent data store
        try{
            $this->save();
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function delete() {
        try{
            parent::delete();
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function getAll() {
        $arr = array();
        $arr['id'] = $this->guid;
        $arr['title'] = $this->title;
        $arr['ratings'] = json_decode($this->ratings);
        $arr['travellers'] = json_decode($this->travellers);
        $arr['location'] = $this->location;
        $arr['start_date'] = $this->start_date;
        $arr['end_date'] = $this->end_date;
        $arr['opi'] = json_decode($this->opi);
        $arr['attendees'] = $this->attendees;
        $arr['purpose'] = $this->purpose;
        $arr['discussions'] = json_decode($this->discussions);
        $arr['issues_of_concern'] = json_decode($this->issues_of_concern);
        $arr['internal_to_sc'] = json_decode($this->internal_to_sc);
        $arr['external_to_sc'] = json_decode($this->external_to_sc);
        $arr['annex'] = json_decode($this->annex);
        $arr['owner'] = get_entity($this->owner_guid)->name;
        $arr['time_created'] = gmdate("Y-m-d", $this->time_created);
        if($this->session->getPublicKey() == $this->owner_guid || elgg_is_admin_user($this->session->getPublicKey())){
            $arr['can_edit'] = true;
        }
        
        return $arr;
    }
    
    public function getRatings()
    {
        return json_decode($this->ratings);
    }
    
    public function canEdit() {
        if($this->session->getPublicKey() == $this->owner_guid || elgg_is_admin_user($this->session->getPublicKey())){
            return true;
        }
        return false;
    }
    
    private function filterArr($arr) {
        foreach($arr as $k=>$v) {
            if(empty($v)) {
                $arr[$k] = array("body"=>"");
            }
        }
        return $arr;
    }
}