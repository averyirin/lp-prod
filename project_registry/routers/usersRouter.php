<?php

function usersRouter($method, $page, $publicKey) {
    switch ($method) {
        case 'PUT':
            //sanitize input
            $payload = json_decode(file_get_contents("php://input"), true);

            $publicKey = $payload['public_key'];
            $userId    = $page[1];

            $session = new Session($publicKey, $signature, $payload);
            $user    = new User($userId);

            if ($user->update($payload)) {
                //return 200
                $session->setheader(200);
            } else {
                //return
                $session->setHeader(500);
                $status = 'error';
                $data   = array('message' => 'There was an error saving the project resource');
            }

            exit;
            break;
        case 'GET':
            if ($page[1]) {
                switch ($page[1]) {
                    case "getUsers":

                        //get params
                        $projectAdmin = get_input('project_admin');
                        $departmentOwner = get_input('department_owner');
                        //fill params array with provided params
                        $projectAdmin ? $params['project_admin'] = $projectAdmin : '';

                        foreach($params as $key => $param) {
                            if($param == 'true') {
                                $params[$key] = true;
                            }
                            elseif($param == 'false') {
                                $params[$key] = false;
                            }
                        }

                        $session = new Session($publicKey, $signature, $params);

                        $user = new User();
                        $user->setCollection($params);

                        $departmentOwner ? $user->filterCollection('department_owner', $departmentOwner) : '';
                        $data = $user->getCollection();
                        $session->setHeader(200);

                        header('Content-type: application/json');

                        echo json_encode(array('data' => $data, 'status' => $status), 32);
                        exit;
                        break;
                    case "getAllUsers":
                        $session = new Session($publicKey, $signature, $params);

                        $user = new User();
                        $user->setUserData($params);

                        $data = $user->getCollection();

                        $session->setHeader(200);

                        header('Content-type: application/json');

                        echo json_encode(array('data' => $data, 'status' => $status), 32);

                        exit;
                        break;
                    default:
                        $session      = new Session($publicKey, $signature, $params);
                        $user         = new User($page[1]);
                        $data['user'] = $user->getAll();
                        $session->setHeader(200);

                        header('Content-type: application/json');
                        echo json_encode(array('data' => $data, 'status' => $status), 32);
                        exit;
                        break;
                }
            } else {

                $session = new Session($publicKey, $signature, $params);

                $columns = get_input("columns");
                $orders  = get_input("order");

                $sort = \null;

                if ($orders && !empty($orders[0])) {
                    $sort = $columns[$orders[0]['column']]['name'] . " " . $orders[0]['dir'];
                }

                $searches = get_input("search");
                $start    = get_input("start");
                $length   = get_input("length");

                $user = new User();

                $dtData = $user->getDatatableData($sort, $searches['value'], $start, $length);

                $users = array();
                foreach ($dtData['limit'] as $u) {
                    $users[] = array(
                        "name"        => $u->name,
                        "email"       => $u->email,
                        "username"    => $u->username,
                        "active"      => $u->active ? $u->active : "no",
                        "deactivated" => $u->deactivated ? $u->deactivated : "no",
                        "actions"     =>
                        ((($u->deactivated == "no" || !$u->deactivated) ? "<a href='#' onclick='putUser(" . $u->guid . ", " . json_encode("activated") . ", " . ($u->deactivated == 'yes' ? "true" : "false") . ")'>Deactivate</a>" : "<a href='#' onclick='putUser(" . $u->guid . ", " . json_encode("activated") . ", " . ($u->deactivated == 'yes' ? "true" : "false") . ")'>Activate</a>") .
                        (($u->active == "no" || !$u->active) ? "<a href='#' onclick='putUser(" . $u->guid . ", " . json_encode("validated") . ", " . "true" . ")'> | Validate</a>" : "") .
                         " | " . "<a href='#' onclick='putUser(" . $u->guid . ", " . json_encode("password") . ", " . "true" . ")'>Reset Password</a>"),
                    );

                    if (count($users) === (int) $length) {
                        break;
                    }
                }

                $data = array(
                    "status"          => $status,
                    "recordsTotal"    => \count($dtData['total']),
                    "recordsFiltered" => \count($dtData['limit']),
                    "data"            => $users,
                );

                $session->setHeader(200);

                header('Content-type: application/json');
                echo json_encode($data, 32);
            }
            exit;
            break;
    }
}
