<?php

$csvFileName = $_FILES['users']['tmp_name'];
$csvFile = fopen($csvFileName, 'r');
while($line = fgetcsv($csvFile)){
	//validation
	if(count($line) > 4){
		register_error('Too many fields in row');
		forward(REFERER);
	}
	elseif(count($line) < 4){
		register_error('Too few fields in row');
		forward(REFERER);
	}
	elseif(!strpos($line[1], "@"))
	{
		register_error('No email in second column');
		forward(REFERER);
	}
	//name,email,username,password for column headers
	$name = $line[0];
	$email = $line[1];
	$username = $line[2];
	$password = $line[3];
	$userGuid = register_user($username, $password, $name, $email, TRUE);
	if($userGuid != false){
		$newUser = get_entity($userGuid);
		$newUser->admin_created = true;
		$newUser->created_by_guid = elgg_get_logged_in_user_guid();
		$newUser->validated = true;
		$newUser->password = true;

	}
}
fclose($csvFile);
