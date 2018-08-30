<?php

	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
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
	'href' => 'http://s3.ongarde.net/portal/pages/view/100/caf-mobile-app-store'
));

elgg_register_menu_item('site', array (
	'name' => 'HowToVideos',
	'text' => elgg_echo('wet:howtovideos'),
	'href' => 'http://s3.ongarde.net/portal/groups/profile/1705/learning-portal-how-to-videos'
));


elgg_register_menu_item('site', array (
        'name' => 'LPR',
        'text' => elgg_echo('wet:lpr'),
        'href' => 'http://s3.ongarde.net/portal/projects/all'
));
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
//overide default river delete action
elgg_unregister_action('river/delete');
elgg_register_action('river/delete', elgg_get_plugins_path()."wettoolkit/actions/river/delete.php");

//add river menu item to delete activity item that belong to the user
elgg_register_plugin_hook_handler('register', 'menu:river', 'custom_river_menu_setup');

/*---------------INACTIVE commented out-----------*/
/*                elgg_unregister_menu_item('site', 'blog');
		elgg_unregister_menu_item('site', 'activity');
		elgg_unregister_menu_item('site', 'file');
		elgg_unregister_menu_item('site', 'members');
		elgg_unregister_menu_item('site', 'pages');
		elgg_unregister_menu_item('site', 'thewire');
		elgg_unregister_menu_item('site', 'bookmarks');
		elgg_unregister_menu_item('site', elgg_echo('tasks'));
                elgg_unregister_menu_item('site', 'answers');
		elgg_unregister_menu_item('site', 'groups');
		elgg_unregister_menu_item('site', 'photos');
		elgg_unregister_menu_item('site', 'translation_editor');	
*/
	
/*--------replace with new menu items---------*/

//register page handler
elgg_register_page_handler("feature", "feature_page_handler");

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
	
	// Register our initialization function. We put a huge priority number to ensure that it runs last and can clear out all existing CSS
	register_elgg_event_handler('init','system','wet_theme_init', 9999999999999);
	
	if(isset($_GET['lang']) && in_array($_GET['lang'], array("en", "fr"))){		
		$_SESSION['lang']=$_GET['lang'];		
		header("Location: ".$_SERVER['HTTP_REFERER']);
		die();
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
