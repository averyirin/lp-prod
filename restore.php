<?php
/**
 * Remove a page
 *
 * Subpages are not deleted but are moved up a level in the tree
 *
 * @package ElggPages
 */

$pageGuid = get_input('page');
$page = get_entity($pageGuid );
$annotationGuid = get_input('annotation');
$annotation = get_entity($annotationGuid);

if (elgg_instanceof($page, 'object', 'page') || elgg_instanceof($page, 'object', 'page_top')) {
	// only allow owners and admin to restore
	if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $page->getOwnerGuid()) {
		$container = get_entity($page->container_guid);
		echo $pageGuid;
		echo "<br/>";
		echo $annotationGuid;
			
		forward("pages/$page->guid");
	}
}

register_error(elgg_echo('pages:restore:failure'));
forward(REFERER);
