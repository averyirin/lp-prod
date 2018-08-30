<?php

function projectsRouter($method, $page, $publicKey) {
    switch ($method) {
        case 'GET':
            if ($page[1]) {
                $session = new Session($publicKey, $signature, $params);

                //load single resource
                $project = Project::withID($page[1], $session);
                $data    = $project;
                $session->setHeader(200);
            } else {
                //retreive all resources with optional filters
                $status       = get_input('status', null);
                $project_type = get_input('project_type', null);
                $department_owner = get_input('department_owner', null);
                $createdAt    = get_input('created_at', null);
                $ownerGuid    = get_input('owner_guid', null);

                $status ? $params['status']       = $status : '';
                $project_type ? $params['project_type'] = $project_type : '';
                $department_owner ? $params['department_owner'] = html_entity_decode($department_owner) : '';
                $createdAt ? $params['created_at']   = $createdAt : '';
                $ownerGuid ? $params['owner_guid']   = $ownerGuid : '';

                $session = new Session($publicKey, $signature, $params);

                $projects = Project::all($params, $session);

                $data = $projects->getCollection();
                $session->setHeader(200);
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
            }
            header('Content-type: application/json');
            echo json_encode(array('status' => $status, 'data' => $data), 32);

            exit;
            break;
        case 'PUT':
            $payload = json_decode(file_get_contents("php://input"), true);

            $session = new Session($publicKey, $signature, $payload);

            $project = Project::withID($page[1], $session);
            if ($project->update($payload)) {
                $session->setHeader(200);
                $status = 'success';
                $data   = array('id' => $project->id);
            } else {
                $session->setHeader(500);
                $data = $project->errors;
            }

            header('Content-type: application/json');
            echo json_encode(array('status' => $status, 'data' => $data), 32);

            exit;
            break;
        case 'DELETE':

            if ($page[1]) {
                $session = new Session($publicKey, $signature, $params);

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
            }

            header('Content-type: application/json');
            echo json_encode(array('status' => $status, 'data' => $data), 32);

            exit;
            break;
    }
}
