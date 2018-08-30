<?php
function tripReportsRouter($method, $page, $publicKey) {
    $session = new Session($publicKey, null, null);
    switch($method){
        case 'GET':
            if($tripReportId = $page[1]){
                //get single resource
                $tripReport = new TripReport($session, $tripReportId);
                $data['trip_report'] = $tripReport->getAll();
                $status = 'success';
                $session->setHeader(200);
            }
            else{
                //get all resources
                //add filter if applicable
                $params = array();
                $owner_guid = get_input('owner_guid');
                $owner_guid ? $params['owner_guid'] = $owner_guid : '';
                
                $tripReportRepository = new TripReportRepository($session);

                if(count($params) > 0) {
                    $tripReportRepository->all($params);
                }
                else{
                    $tripReportRepository->all();
                }
                
                $data['trip_reports'] = $tripReportRepository->getAll();
                $status = 'success';
                $session->setHeader(200);
            }
            
            echo $session->sendResponse($status, $data);
            exit;
            break;
        case 'POST':
            $payload = json_decode(file_get_contents("php://input"), true);
            
            if($tripReportId = $page[1]){
                if($page[2] == 'ratings') {
                    $tripReport = TripReport::withId($tripReportId, $session);
                    
                    if($tripReport->addRating($payload['rating'])) {
                        $session->setHeader(200);
                        $status = 'success';
                        $data['ratings'] = $tripReport->getRatings();
                    }
                    else{
                        $session->setHeader(500);
                        $status = 'fail';
                        $data['message'] = $tripReport->session->errors;
                    }
                }
            }
            else{
                $tripReport = TripReport::newObj($payload['trip_report'], $session);
                
                if($tripReport->validate()) {
                    if($tripReport->create()){
                        $session->setHeader(201);
                        $data['trip_report'] = $tripReport->getAll();
                        $status = 'success';
                    }
                    else{
                        $session->setHeader(500);
                        $status = 'fail';
                        $data['message'] = $tripReport->session->errors;
                    }
                }
                else{
                    $session->setHeader(400);
                    $status = 'error';
                    $data['message'] = $tripReport->session->errors;
                }
            }
            
            echo $session->sendResponse($status, $data);
            exit;
            break;
        case 'PUT':
            $payload = json_decode(file_get_contents("php://input"), true);
            
            if($tripReportId = $page[1]) {
                $tripReport = TripReport::withId($tripReportId, $session);
                
                if($tripReport->update($payload)){
                    $session->setHeader(200);
                    $status = 'success';
                }
                else{
                    $session->setHeader(400);
                    $data['message'] = $tripReport->session->errors;
                    $status = 'fail';
                }
            }
            else{
                $session->setHeader(400);
                $status = 'error';
            }
            
            echo $session->sendResponse($status, $data);
            exit;
            break;
        case 'DELETE':
            
            if($tripReportId = $page[1]) {
                $tripReport = TripReport::withId($tripReportId, $session);
                
                if($tripReport->delete()){
                    $session->setHeader(200);
                    $status = 'success';
                }
                else{
                    $session->setHeader(500);
                    $status = 'fail';
                }
            }
            else{
                $session->setHeader(400);
                $status = 'error';
            }
            
            echo $session->sendResponse($status, $data);
            exit;
            break;
    }
}