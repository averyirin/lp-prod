<?php
elgg_make_sticky_form('semadd');

$svc = get_input('svc');
$name = get_input('name');
$admin = get_input('admin');
if(is_array($admin)){
	$admin = $admin[0];
}
if($svc == '' || $name == ''){
	register_error(elgg_echo('register:fields'));
	forward(REFERER);
}
$nameArr = explode(" ",$name);
$username = "";
foreach($nameArr as $k=>$v){
	$username.= $v;
	if(++$k != count($nameArr)){
		$username .=".";
	}
}

$curYear = date("Y");
$password = "changeme".$curYear;
$svcArr = explode(" ",$svc);

$email = "";
foreach($svcArr as $i=>$j){
	$email .= $j;
}
$email .= "@forces.gc.ca";

$message = "Good day,<br/><br/>";
$message .="Subject's Learning Portal account has been created.<br/>";
$message .="Username: ".$username."<br/>";
$message .="Email: ".$email."<br/>";
$message .="Password: ".$password ."<br/><br/>";

$message .="Thanks,<br/><br/>";

$message .= "<a href='/portal/admin/users/semadd'>Continue after sending email</a>";


try{

	$guid = register_user($username, $password, $name, $email, TRUE);
	if($guid){
		$new_user = get_entity($guid);
		elgg_clear_sticky_form('semadd');
		$new_user->admin_created = true;
		$new_user->created_by_guid = elgg_get_logged_in_user_guid();
		$new_user->validated = true;
		$new_user->password = true;

		
		system_message(elgg_echo("adduser:ok", array(elgg_get_site_entity()->name)));
		echo $message;
		exit;
		
	}else{
		register_error(elgg_echo("adduser:bad"));
	}

}catch(RegistrationException $r){
	register_error($r->getMessage());
}