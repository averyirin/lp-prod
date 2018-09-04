<?php
/**
 * Elgg Webservice plugin 
 * 
 * @package Webservice
 * @author Mark Harding (based on work started by Saket Saurabh)
 *
 */
function apiInit() {
    require_once(dirname(__FILE__) . "/routers/projectsRouter.php");
    require_once(dirname(__FILE__) . "/routers/usersRouter.php");
    require_once(dirname(__FILE__) . "/routers/tripReportsRouter.php");
    require_once(dirname(__FILE__) . "/routers/confluenceSpaceRouter.php");
	elgg_register_library('api:core', elgg_get_plugins_path() . 'api/lib/core.php');
	elgg_load_library('api:core');
	elgg_register_page_handler('api', 'apiPageHandler');
	
	elgg_register_page_handler('internapi', 'internApiPageHandler');
}

elgg_register_event_handler('init', 'system', 'apiInit');
