<?php
/**
 * Created by PhpStorm.
 * User: Irin A
 * Date: 2/2/2018
 * Time: 9:11 AM
 */

//returns elements that are not in the Project Charter array
function not_have_charter_array($spaceArray, $charterArray) {
    foreach ($spaceArray as $key => $value) {
        if (in_array($value, $charterArray)) {
            unset($spaceArray[$key]);
        }
    }
    return $spaceArray;
}

function confluenceSpaceRouter($method, $page, $publicKey)
{    

	$proxy = '10.239.238.50:8080';
	$useProxy = true;
    switch ($method) {
        case 'GET':
            //If we have an ID, returns if we have the Project Charter in Confluence or not
            if ($page[1]) {
                $isInConfluence = false;
                $project_guid = $page[1];
                $project = get_entity($project_guid);
                $session = new Session($publicKey, $signature, $params);
                //If we do not have space key set
                //We have yet to push to confluence
                $spaceKey = $project->spaceKey;
                if ($spaceKey == null) {
                    $isInConfluence = false;
                    $session->setHeader(200);
                    $status = 'Do not have Space Key saved in LP DB';
                    echo json_encode(array('status' => $status, 'data' => $isInConfluence), 32);
                } else {
                    //We have space key set
                    //Test to see if we have the Space in confluence
                    //And the Project Charter page in that space
                    $pageTitle = "Project+Charter";
                    $ch = curl_init();
                    //sets the username and password
                    //get request for space and document
                    curl_setopt($ch, CURLOPT_USERPWD, "bordenstu:ortermatex");
                    $url = "https://confluence.ongarde.net/rest/api/content?title=" . $pageTitle . "&spaceKey=" . $spaceKey;
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if($useProxy){
                    curl_setopt($ch, CURLOPT_PROXY, $proxy);
			}
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    //We found the space in confluence
                    if ($httpCode == 200) {
                        $session->setHeader(200);
                        $response_json = json_decode($response, true);
                        //If we have a Project Charter
                        if (sizeof($response_json['results']) > 0) {
                            $status = 'Found Project Charter in key ' . $spaceKey;
                            $base = $response_json['_links']['base'];
                            $itemUrl = $response_json['results'][0]['_links']['webui'];
                            $url = $base.$itemUrl;


                            $isInConfluence = true;
                            echo json_encode(array('status' => $status, 'data' => $isInConfluence, 'url'=>$url), 32);
                            //We do not have a project charter
                        } else {
                            $status = 'No Project Charter found in key: ' . $spaceKey;
                            $isInConfluence = false;
                            echo json_encode(array('status' => $status, 'data' => $isInConfluence), 32);
                        }
                        //We could not find the space
                    } else {
                        //echo $response;
                        $status = 'No Space in Confluence with key: ' . $spaceKey;
                        $isInConfluence = false;
                        echo json_encode(array('status' => $status, 'data' => $isInConfluence), 32);
                    }
                }
                exit;
            } else {
                //GET all Spaces that do not have a Project Charter in them
                $status = get_input('status', null);
                $session = new Session($publicKey, $signature, $params);


                $ch = curl_init();
                //sets the username and password
                //get request for space and document
                curl_setopt($ch, CURLOPT_USERPWD, "bordenstu:ortermatex");
                $pageTitle = "'Project+Charter'";
                $cql = "(title=".$pageTitle.")&expand=space&limit=99999";
                $url = "https://confluence.ongarde.net/rest/api/content/search?cql=".$cql;
		if($useProxy){
                curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $projectChatertResponse = curl_exec($ch);
                $projectCharterHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $response_json = json_decode($projectChatertResponse, true);

                $projectCharterArr = array();
                foreach ($response_json['results'] as $item){
                    array_push($projectCharterArr ,array('key'=> $item['space']['key'], 'name'=> $item['space']['name'] ) );
                }

                $spacesCh = curl_init();
                //sets the username and password
                //get request for space and document
                curl_setopt($spacesCh, CURLOPT_USERPWD, "bordenstu:ortermatex");
                $url = "https://confluence.ongarde.net/rest/api/space?limit=99999";
                curl_setopt($spacesCh, CURLOPT_RETURNTRANSFER, true); 
		if($useProxy){
		curl_setopt($spacesCh, CURLOPT_PROXY, $proxy);      
		}         
                curl_setopt($spacesCh, CURLOPT_URL, $url);
                curl_setopt($spacesCh, CURLOPT_SSL_VERIFYPEER, false);
                $spacesResponse = curl_exec($spacesCh);
                $spacesHttpCode = curl_getinfo($spacesCh, CURLINFO_HTTP_CODE);
                curl_close($spacesCh);

                //Create two arrays, one for spaces, one for spaces with Project Charter
                $spaceArr = array();
                $response_json = json_decode($spacesResponse, true);
                foreach ($response_json['results'] as $item){
                    array_push($spaceArr, array('key'=> $item['key'], 'name'=> $item['name'] ));
                }
                //get the ones that do not have project charter and return them
                $diffArr= not_have_charter_array($spaceArr, $projectCharterArr);
                $arrResultSpaces = array();
                foreach ($diffArr as $item){
                    array_push($arrResultSpaces, array('key'=>$item['key'], 'name'=> $item['name']));
                }
                $results = array(
                    "results" => $arrResultSpaces
                );
                //translate resulting array to json
                $resultSpaces = json_encode($results);

                // return results if successful
                if ($spacesHttpCode == 200 && $projectCharterHttpCode == 200) {
                    echo $resultSpaces;
                } else if ($spacesHttpCode == 401 ||  $projectCharterHttpCode == 401) {
                    $session->setHeader(401);
                    $status = 'unauthorized';
                    $message = " You are unauthorized to see list of spaces";
                    echo json_encode(array('status' => $status, 'data' => $message), 32);

                } else if ($spacesHttpCode == 404 || $projectCharterHttpCode == 404) {
                    $session->setHeader(404);
                    $status = 'notfound';
                    $message = " Could not find list of spaces";
                    echo json_encode(array('status' => $status, 'data' => $message), 32);

                } else {
                    $session->setHeader(404);
                    $status = 'error';
                    $message = " Something went wrong, please contact administrator.";
                    echo json_encode(array('status' => $status, 'data' => $message), 32);
                }
                exit;
            }
            break;
        case 'POST':
            //create new Project Charter document, in a new or old space
            $payload = json_decode(file_get_contents("php://input"), true);
            $session = new Session($publicKey, $signature, $payload);
            header('Content-type: application/json');

            //Get variables from payload
            $body = $payload['body'];
            $spaceKey = $payload['spaceKey'];
            $project_guid = $payload['projectGuid'];
            $spaceName = $payload['spaceName'];


            //New space
            if ($payload['addMethod'] == "new") {
                //create new space
                $chSpace = curl_init();
                curl_setopt($chSpace, CURLOPT_USERPWD, "bordenstu:ortermatex");
                curl_setopt($chSpace, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                curl_setopt($chSpace, CURLOPT_RETURNTRANSFER, true);
		if($useProxy){
		curl_setopt($chSpace, CURLOPT_PROXY, $proxy);
		}
                curl_setopt($chSpace, CURLOPT_URL, 'https://confluence.ongarde.net/rest/api/space');
                curl_setopt($chSpace, CURLOPT_POST, true);
                curl_setopt($chSpace, CURLOPT_SSL_VERIFYPEER, false);
                $newSpace = array(
                    "key" => $spaceKey,
                    "name" => $spaceName,
                );
                curl_setopt($chSpace, CURLOPT_POSTFIELDS, json_encode($newSpace));
                $spaceResponse = curl_exec($chSpace);
                curl_close($chSpace);
            }
            //Create document
            //Get the homepage id, the ancestor for our new project charter document
            $chHomepage = curl_init();
            //sets the username and password
		if($useProxy){
 	    curl_setopt($chHomepage, CURLOPT_PROXY, $proxy); 
		}              
            curl_setopt($chHomepage, CURLOPT_USERPWD, "bordenstu:ortermatex");
            curl_setopt($chHomepage, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chHomepage, CURLOPT_URL, 'https://confluence.ongarde.net/rest/api/space/' . $spaceKey .
                '?expand=homepage');
            curl_setopt($chHomepage, CURLOPT_SSL_VERIFYPEER, false);
            $homepageResponse = curl_exec($chHomepage);
            curl_close($chHomepage);
            //decodes json to assoc array
            $homepage_json = json_decode($homepageResponse, true);

            //we enable add to confluence button if found
            $spaceHomepageId = $homepage_json["homepage"]['id'];

            //sets the name of the document
            $documentName = "Project Charter";
            //create project charter page in parent space with the ancestor set to the home page
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERPWD, "bordenstu:ortermatex");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
 		if($useProxy){
	    curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, 'https://confluence.ongarde.net/rest/api/content');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //creating the charter page
            $projectCharter = array(
                "type" => "page",
                "title" => $documentName,
                "space" => array("key" => $spaceKey),
                "ancestors" => array(array("type" => "page", "id" => $spaceHomepageId)),
                "body" => array("storage" => array("value" => $body,
                    "representation" => "storage")));

            //translate to json and send the request
            $jsonCharter = json_encode($projectCharter);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonCharter);
            $charterResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $response_json = json_decode($charterResponse, true);

            if ($httpCode == 200) {
                //Create annotation for Space Key in Project
                $project = get_entity($project_guid);
                $project->spaceKey = $spaceKey;

                //Return successful upload message
                $session->setHeader(200);
                $status = 200;
                $message = ' Project Charter has been uploaded.';
                echo json_encode(array('status' => $status, 'data' => $message), 32);

            } else if ($httpCode == 400) {
                $session->setHeader(400);
                $status = 'alreadyexists';
                $message = " Sorry, a page named Project Charter already exists in space " . $spaceKey;
                echo json_encode(array('status' => $status, 'data' => $message), 32);

            } else if ($httpCode == 401) {
                $session->setHeader(401);
                $status = 'unauthorized';
                $message = " You are unauthorized to create new pages";
                echo json_encode(array('status' => $status, 'data' => $message), 32);

            } else if ($httpCode == 404) {
                $session->setHeader(404);
                $status = 'notfound';
                $message = " Could not find space";
                echo json_encode(array('status' => $status, 'data' => $message), 32);

            } else {
                $session->setHeader(404);
                $status = 'error';
                $message = " Something went wrong, please contact administrator.";
                echo json_encode(array('status' => $status, 'data' => $message), 32);
            }
            exit;
            break;
    }

};
