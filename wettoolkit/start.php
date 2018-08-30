<?php

/**
 * 
 * @author Canadian Defence Academy - Canadian Advanced Distributed Learning Lab
 * @copyright Government of Canada 2013
 * @link http://www.forces.gc.ca/en/training-prof-dev/canadian-defence-academy.page
 */


// Initialization of the plugin.
// We clear out all previously included CSS views that other plugins have put in (using elgg_extend_view)

function wet_theme_init() {
	//Remove topbar elgg logo
	elgg_unregister_menu_item('topbar', 'elgg_logo');
	elgg_extend_view('css/elgg', 'search/css');

	/*----------Custom Menu Items---------------*/

	elgg_register_menu_item ('site', array (
		'name' => 'Home',
		'text' => elgg_echo('wet:home'),
		'href' => '/'
	));

	elgg_unregister_menu_item('site', 'groups');

	elgg_register_menu_item('site', array (
		'name' => 'Groups',
		'text' => elgg_echo('wet:groups'),
		'href' => 'groups/all?filter=newest'
	));

	elgg_register_menu_item('site', array (
		'name' => 'MobileApps',
		'text' => elgg_echo('wet:mobileapp'),
		'href' => 'http://www.canada.ca/en/mobile/'
	));

	/*elgg_register_menu_item('site', array (
		'name' => 'HowToVideos',
		'text' => elgg_echo('wet:howtovideos'),
		'href' => 'http://s3.ongarde.net/portal/groups/profile/1705/learning-portal-how-to-videos'
	));


	elgg_register_menu_item('site', array (
			'name' => 'LPR',
			'text' => elgg_echo('wet:lpr'),
			'href' => 'http://s3.ongarde.net/portal/projects/all'
	));*/

	if (elgg_is_logged_in()) {
		$user_guid = elgg_get_logged_in_user_guid();
		$address = urlencode(current_page_url());
		elgg_unregister_menu_item('extras', 'bookmark');
		elgg_register_menu_item('extras', array(
			'name' => 'bookmark',
			'text' => elgg_view_icon('add-bookmark'),
			'href' => "bookmarks/add/$user_guid?address=$address",
			'title' => elgg_echo('bookmarks:this'),
			'rel' => 'nofollow',
			'id' => 'add-bookmark'
		));
	}

	//register "Configure your tool" and "Account statistics" menu items for admins only
	elgg_register_event_handler('pagesetup', 'system', 'userSettingsSetup');

	//overide default river delete action
	elgg_unregister_action('river/delete');
	elgg_register_action('river/delete', elgg_get_plugins_path()."wettoolkit/actions/river/delete.php");

	//overide default add comments action
	elgg_unregister_action('comments/add');
	elgg_register_action('comments/add', elgg_get_plugins_path()."wettoolkit/actions/comments/add.php");

	//overide default register action
	elgg_unregister_action('register');
	elgg_register_action('register', elgg_get_plugins_path()."wettoolkit/actions/register.php", "public");

	//add river menu item to delete activity item that belong to the user
	elgg_register_plugin_hook_handler('register', 'menu:river', 'custom_river_menu_setup');

	//register page handler
	elgg_register_page_handler("feature", "feature_page_handler");
	elgg_register_page_handler("users", "users_page_handler");
	elgg_unregister_page_handler("register");

}

function custom_river_menu_setup($hook, $type, $return, $params){
	$item = $params['item'];
	$options = array(
				'name' => 'delete',
				'href' => elgg_add_action_tokens_to_url("action/river/delete?id=$item->id&subjectId=$item->subject_guid"),
				'text' => elgg_view_icon('delete'),
				'title' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				'priority' => 200,
	);
	$return[] = ElggMenuItem::factory($options);

	return $return;
}

function feature_page_handler($page){
	switch($page[0]){
		case 'hasSeen':
			$feature = $page[1];
			$featureTour = new NewFeatureTour();
			$result = $featureTour->hasReadDialog($feature);
			echo $result;
		exit;
		break;
		case 'seen':
			$feature = $page[1];
			$featureTour = new NewFeatureTour();
			$featureTour->markAsRead($feature);
		exit;
		break;
	}
}

function users_page_handler($page){
	
	$base_dir = elgg_get_plugins_path() . 'wettoolkit/pages';
	$angular_dir = elgg_get_plugins_path() . 'wettoolkit/js/views';
	
	if (!isset($page[0])) {
		$page[0] = 'all';
	}
	
	switch($page[0]){
		case 'get':
			$users = elgg_get_entities(array(
				"types" => "user",
				"limit" => false
			));
			$return = array();

			foreach($users as $user){
				$obj = array();
				$obj['id'] =  $user->guid;
				$obj['name'] =  $user->name;
				$obj['username'] =  $user->username;
				$return[] = $obj;
			}
			echo json_encode($return);
			exit;
			break;
		case 'registered':
			/*$name = $_GET['name'];
			$email = $_GET['email'];*/
			set_input('name', $page[1]);
			set_input('email', $page[2]);
			include(elgg_get_plugins_path()."wettoolkit/pages/registered.php");
			break;
		
		case 'all':
			include "$base_dir/all.php";
			break;
		case 'register':
			include "$angular_dir/register.php";
			break;
                case 'confirm':
                    set_input('name', $_GET['name']);
                    set_input('email', $_GET['email']);
                    include "$angular_dir/confirm.php";
                    break;
                case 'messages':
                    include "$angular_dir/messages.php";
                    break;
		default:
			return false;
	}
	return true;
}

function alternate_register_page_handler($page_elements, $handler) {
	include(elgg_get_plugins_path()."wettoolkit/pages/account/register.php");
	return true;
}

function userSettingsSetup()
{
	elgg_unregister_menu_item('page', '1_plugins');
	elgg_unregister_menu_item('page', '1_statistics');

	/*
	if(elgg_is_admin_logged_in()){
		$params = array(
			'name' => '1_plugins',
			'text' => elgg_echo('usersettings:plugins:opt:linktext'),
			'href' => "settings/plugins/{$user->username}",
		);
		elgg_register_menu_item('page', $params);

		$params = array(
			'name' => '1_statistics',
			'text' => elgg_echo('usersettings:statistics:opt:linktext'),
			'href' => "settings/statistics/{$user->username}",
		);
		elgg_register_menu_item('page', $params);
	}
	*/
}
	// Register our initialization function. We put a huge priority number to ensure that it runs last and can clear out all existing CSS
	register_elgg_event_handler('init','system','wet_theme_init', 9999999999999);

	if(isset($_GET['lang'])){
        if(substr($_GET['lang'], 2, 1) == '/') {
            $_GET['lang'] = substr($_GET['lang'], 0, 2);
        }
        
        if(in_array($_GET['lang'], array("en", "fr"))){		
            $_SESSION['lang']=$_GET['lang'];
            
            if(isset($_GET['external_req'])) {
                header("Location: ".elgg_get_site_url());
            }
            else{
                header("Location: ".$_SERVER['HTTP_REFERER']);
            }
            die();
        }
	}
	if(isset($_SESSION['lang'])){
		$user=elgg_get_logged_in_user_entity();
		if($user){
			$user->language=$_SESSION['lang'];
			$user->save();
		} else {		
			elgg_set_config("language",$_SESSION['lang']);
		}
	}
		
	
?>
