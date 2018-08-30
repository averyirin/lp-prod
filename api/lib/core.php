<?php

function apiPageHandler($page) {
    //common vars for all resources
    $headers = apache_request_headers();

    $method    = $_SERVER['REQUEST_METHOD'];
    $publicKey = get_input('public_key');
    $signature = $headers['Signature'];

    $params               = array();
    $params['public_key'] = $publicKey;

    switch ($page[0]) {
        case 'authenticate':
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Allow-Origin: *");
            switch ($method) {
                case 'POST':
                    $authenticate = new Authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

                    if ($authenticate->validate()) {

                        //check if user is logging in with email
                        $authenticate->checkUsername();

                        if ($authenticate->login()) {
                            header("HTTP/1.1 200 OK");

                            $responseInfo = $authenticate->getResponseInfo();

                            $data['userId']     = $responseInfo['userId'];
                            $data['publicKey']  = $responseInfo['publicKey'];
                            $data['privateKey'] = $responseInfo['privateKey'];
                            $status             = 'success';
                        } else {
                            //unauthorized
                            header('X-PHP-Response-Code: 401', true, 401);

                            $data   = $authenticate->errors;
                            $status = 'fail';
                        }
                    } else {
                        //model validation has failed - client error
                        header('X-PHP-Response-Code: 400', true, 400);

                        $status = 'fail';
                        $data   = $authenticate->errors;
                    }
                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data));

                    exit;
                    break;
            }
            exit;
            break;
        case 'notifications':
            $signature = $headers['Signature'];
            switch ($method) {
                case 'POST':
                    $payload   = json_decode(file_get_contents("php://input"), true);
                    $publicKey = $payload['public_key'];
                    $session   = new Session($publicKey, $signature, $payload);

                    if ($session->verifySignature()) {
                        $notification = new Notification($payload['to_id'], $payload['subject'], $payload['body']);
                        if ($notification->send()) {
                            $session->setHeader(201);
                            $status = 'success';
                        } else {
                            $session->setHeader(500);
                            $status = 'error';
                            $data   = array('message' => 'There was an error sending the notification');
                        }
                    } else {
                        $session->setHeader(401);
                        $status = 'fail';
                        $data   = array('message' => 'Insufficient access privledges');
                    }
                    exit;
                    break;
            }
            exit;
            break;
        case 'users':
            $signature = $headers['Signature'];
            switch ($method) {
                case 'PUT':
                    //sanitize input
                    $payload = array();
                    $payload = json_decode(file_get_contents("php://input"), true);

                    $userId = $page[1];
                    $action = $page[2];

                    $session = new Session($publicKey, $signature, $payload);

                    if ($action == 'disable') {
                        $user = new User($userId, $session);

                        if ($user->disable()) {
                            $session->setHeader(201);
                            $status        = 'success';
                            $data          = array();
                            $data['id']    = $user->getId();
                            $data['name']  = $user->getName();
                            $data['email'] = $user->getEmail();
                        } else {
                            $session->setHeader(500);
                            $status = 'fail';
                            $data   = $user->session->errors;
                        }
                    } else {
                        $publicKey = $payload['public_key'];


                        $session = new Session($publicKey, $signature, $payload);

                        if ($session->verifySignature()) {
                            $user = new User($userId);

                            if ($user->validate()) {
                                if ($user->update($payload)) {
                                    //return 200
                                    $session->setheader(200);
                                } else {
                                    //return
                                    $session->setHeader(500);
                                    $status = 'error';
                                    $data   = array('message' => 'There was an error saving the project resource');
                                }
                            } else {
                                //return 400 - client error
                                $session->setheader(400);
                            }
                        } else {
                            //return 401 - unauthorized
                            $session->setheader(401);
                        }
                    }

                    echo $session->sendResponse($status, $data);
                    exit;
                    break;

                case 'GET':
                    if ($page[1]) {
                        $session = new Session($publicKey, $signature, $params);
                        if ($session->verifySignature()) {
                            $user = new User($page[1]);
                            $user->setUser();
                            $data = $user;
                            $session->setHeader(200);
                        } else {
                            $session->setHeader(401);
                            $status = 'fail';
                            $data   = array('message' => 'Insufficient access privledges');
                        }
                    } else {
                        $projectAdmin            = get_input('project_admin');
                        $projectAdmin ? $params['project_admin'] = $projectAdmin : '';

                        foreach ($params as $key => $param) {
                            if ($param == 'true') {
                                $params[$key] = true;
                            } elseif ($param == 'false') {
                                $params[$key] = false;
                            }
                        }

                        $session = new Session($publicKey, $signature, $params);

                        if ($session->verifySignature()) {
                            $user = new User();
                            $data = $user->getAll($params);
                            $session->setHeader(200);
                        } else {
                            $session->setHeader(401);
                            $status = 'fail';
                            $data   = array('message' => 'Insufficient access privledges');
                        }
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
                case 'POST':
                    $session = new Session($publicKey, $signature, $params);

                    if ($userId = $page[1]) {

                    } else {
                        $payload = json_decode(file_get_contents("php://input"), true);
                        $user    = User::newUser($payload, $session);

                        if ($user->validate()) {
                            if ($user->create()) {
                                $session->setHeader(201);
                                $status       = 'success';
                                $data         = array();
                                $data['id']   = $user->getId();
                                $data['name'] = $user->getName();
                            } else {
                                $session->setHeader(500);
                                $status = 'fail';
                                $data   = $user->session->errors;
                            }
                        } else {
                            $session->setHeader(400);
                            $status = 'error';
                            $data   = $user->session->errors;
                        }
                    }
                    echo $session->sendResponse($status, $data);
                    exit;
                    break;
            }
            exit;
            break;

        case 'projects':
            switch ($method) {
                case 'GET':
                    if ($page[1]) {
                        $session = new Session($publicKey, $signature, $params);
                        if ($session->verifySignature()) {
                            //load single resource
                            $project = Project::withID($page[1], $session);
                            $data    = $project;
                            $session->setHeader(200);
                        } else {
                            $session->setHeader(401);
                            $status = 'fail';
                            $data   = array('message' => 'Insufficient access privledges');
                        }
                    } else {
                        //retreive all resources with optional filters
                        $status    = get_input('status', null);
                        $createdAt = get_input('created_at', null);
                        $ownerGuid = get_input('owner_guid', null);

                        $status ? $params['status']     = $status : '';
                        $createdAt ? $params['created_at'] = $createdAt : '';
                        $ownerGuid ? $params['owner_guid'] = $ownerGuid : '';

                        $session = new Session($publicKey, $signature, $params);

                        if ($session->verifySignature()) {
                            $projects = Project::all($params, $session);

                            $data = $projects->getCollection();
                            $session->setHeader(200);
                        } else {
                            $session->setHeader(401);
                            $status = 'fail';
                            $data   = array('message' => 'Insufficient access privledges');
                        }
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
                case 'POST':
                    if (get_input('action') == 'attachFile') {
                        $session = new Session(null, null, null);
                        if (Project::saveAttachments($_FILES['files'], $_POST['projectId'], $_POST['accessId'])) {
                            $session->setHeader(200);

                            $status = 'success';
                        } else {
                            $session->setHeader(400);
                            $status = 'error';
                        }
                    } else {
                        $payload            = json_decode(file_get_contents("php://input"), true);
                        $payload['user_id'] = (int) $payload['user_id'];

                        $session = new Session($publicKey, $signature, $payload);

                        if ($session->verifySignature()) {
                            //check if edit or creation
                            if ($page[1]) {
                                $project = Project::withID($page[1], $session);

                                if ($project) {
                                    if ($project->can_edit) {
                                        if ($project->validate()) {
                                            if ($project->edit($payload)) {
                                                $data = $project;
                                                $session->setHeader(200);
                                            } else {
                                                $session->setHeader(500);
                                                $status = 'error';
                                                $data   = array('message' => 'There was an error saving the project resource');
                                            }
                                        } else {
                                            $session->setHeader(400);
                                            $status = 'fail';
                                            $data   = $project->errors;
                                        }
                                    } else {
                                        $session->setHeader(401);
                                        $status = 'fail';
                                        $data   = array('message' => 'Insufficient access privledges');
                                    }
                                } else {
                                    $session->setHeader(404);
                                    $status = 'error';
                                }
                            } else {
                                $project = Project::withParams($payload);

                                if ($project->validate()) {
                                    if ($project->create()) {
                                        $session->setHeader(201);
                                        $status = 'success';
                                        $data   = array('id' => $project->id, 'accessId' => $project->access_id);
                                        $project->sendEmail('submit');
                                    } else {
                                        $session->setHeader(500);
                                    }
                                } else {
                                    $session->setHeader(400);
                                    $data = $project->errors;
                                }
                            }
                        } else {
                            $session->setHeader(401);
                        }
                    }
                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
                case 'PUT':
                    $payload = json_decode(file_get_contents("php://input"), true);

                    $session = new Session($publicKey, $signature, $payload);

                    if ($session->verifySignature()) {
                        $project = Project::withID($page[1], $session);
                        if ($project->update($payload)) {
                            $session->setHeader(200);
                            $status = 'success';
                            $data   = array('id' => $project->id);
                        } else {
                            $session->setHeader(500);
                        }
                    } else {
                        //return 401 - unauthorized
                        $session->setheader(401);
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
                case 'DELETE':

                    if ($page[1]) {
                        $session = new Session($publicKey, $signature, $params);
                        if ($session->verifySignature()) {
                            $project = Project::withID($page[1], $session);
                            if ($project) {
                                $result = Project::delete($project);

                                if ($result) {
                                    $session->setHeader(200);
                                    $status = 'success';
                                    $data   = null;
                                } else {
                                    $session->setHeader(500);
                                    $status = 'fail';
                                }
                            } else {
                                $session->setHeader(404);
                                $status = 'error';
                            }
                        } else {
                            $session->setHeader(401);
                            $status = 'error';
                        }
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
            }
            exit;
            break;

        case 'language':
            switch ($method) {
                case 'GET':
                    $plugin = $page[1];

                    if (isset($plugin) && !empty($plugin)) {
                        $session = new Session($publicKey, $signature, $params);

                        if ($session->verifySignature()) {
                            $language = new Language($plugin, $session);
                            $result   = $language->getObjects();
                            if ($result) {
                                $session->setHeader(200);
                                $status = 'success';
                                $data   = $result;
                            } else {
                                $session->setHeader(404);
                                $status = 'error';
                                $data   = array('message' => 'The plugin given could not be found.');
                            }
                        } else {
                            $session->setHeader(401);
                            $status = 'error';
                        }
                    }
                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);
                    exit;
                    break;
            }
            exit;
            break;

        default:
            exit;
            break;
    }
    return true;
}

function internApiPageHandler($page) {
    //common vars for all resources
    $headers = apache_request_headers();

    $method    = $_SERVER['REQUEST_METHOD'];
    $publicKey = get_input('public_key');

    $params               = array();
    $params['public_key'] = $publicKey;

    switch ($page[0]) {
        case 'notifications':
            switch ($method) {
                case 'POST':
                    $payload   = json_decode(file_get_contents("php://input"), true);
                    $publicKey = $payload['public_key'];
                    $session   = new Session($publicKey, $signature, $payload);

                    $notification = new Notification($payload['to_id'], $payload['subject'], $payload['body']);
                    if ($notification->send()) {
                        $session->setHeader(201);
                        $status = 'success';
                    } else {
                        $session->setHeader(500);
                        $status = 'error';
                        $data   = array('message' => 'There was an error sending the notification');
                    }
                    exit;
                    break;
            }
            exit;
            break;
        case 'users':
            usersRouter($method, $page, $publicKey);
            exit;
            break;

        case 'trip_reports' :
            tripReportsRouter($method, $page, $publicKey);
            exit;
            break;
        case 'projects':
            projectsRouter($method, $page, $publicKey);
            exit;
            break;
        case 'files':
            switch ($method) {
                case 'GET':

                    exit;
                    break;
                case 'POST':
                    $payload = array();

                    $payload         = $_POST['fileData'];
                    $payload['file'] = $_FILES['file'];

                    $session = new Session($publicKey, $signature, $payload);
                    $file    = new File(null, $payload, $session);

                    if ($file->validate()) {
                        set_input('categories', $payload['categories']);
                        if ($file->create()) {
                            $session->setHeader(201);
                            $status          = 'success';
                            $data['file']    = $file->getAll();
                            $data['message'] = elgg_echo("file:saved");
                        } else {
                            $session->setHeader(500);
                            $status          = 'fail';
                            $data['message'] = $file->session->errors;
                        }
                    } else {
                        $session->setHeader(400);
                        $status          = 'error';
                        $data['message'] = $file->session->errors;
                    }

                    echo $session->sendResponse($status, $data);
                    exit;
                    break;
                case 'PUT':

                    exit;
                    break;
                case 'DELETE':
                    if ($page[1]) {
                        $session = new Session($publicKey, $signature, $params);

                        $file = new File($page[1]);
                        if ($file) {
                            $result = $file->delete();

                            if ($result) {
                                $session->setHeader(200);
                                $status = 'success';
                                $data   = null;
                            } else {
                                $session->setHeader(500);
                                $status = 'fail';
                            }
                        } else {
                            $session->setHeader(404);
                            $status = 'error';
                        }
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
            }

            exit;
            break;

        case 'entity':
            switch ($method) {
                case 'GET':
                    if ($page[1]) {
                        $session        = new Session($publicKey, $signature, $params);
                        $entity         = new Entity($page[1], $session);
                        $data['entity'] = $entity->getAll();
                        $session->setHeader(200);
                    }

                    header('Content-type: application/json');
                    echo json_encode(array('status' => $status, 'data' => $data), 32);

                    exit;
                    break;
            }

            exit;
            break;
        default:
            exit;
            break;
    }
    return true;
}
