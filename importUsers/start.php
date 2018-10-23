<?php

elgg_register_event_handler('init', 'system', 'importUsersInit');

function importUsersInit(){
        elgg_register_menu_item('page', array(
                'name' => 'userImport',
                'href' => 'import/users',
                'text' => elgg_echo('Import Users'),
                'context' => 'admin',
                'priority' => 20,
                'section' => 'administer'
        ));


	$actionPath = elgg_get_plugins_path()."importUsers/actions";

	elgg_register_action('import/users', $actionPath."/import/users.php", 'admin');

	elgg_register_page_handler('import', 'importUsersPageHandler');
}

function importUsersPageHandler($page){
	admin_gatekeeper();
	elgg_admin_add_plugin_settings_menu();
	elgg_set_context('admin');

	elgg_unregister_css('elgg');
	elgg_load_js('elgg.admin');
	elgg_load_js('jquery.jeditable');

	$vars = array('page' => $page);
	$view = 'import/' . implode('/',$page);
	$title = "Import Users";

	$content = elgg_view($view);

	$body = elgg_view_layout('admin', array('content' => $content, 'title' => $title));
	echo elgg_view_page($title, $body, 'admin');
	return true;
}