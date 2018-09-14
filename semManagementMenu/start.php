<?php
function semManagementMenuInit() {
	elgg_register_admin_menu_item('administer', 'semadd', 'users', 50);
	elgg_register_action('semadd', elgg_get_plugins_path().'semManagementMenu/actions/semadd.php');


}

elgg_register_event_handler('init', 'system', 'semManagementMenuInit');
