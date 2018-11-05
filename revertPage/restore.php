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
$annotation = elgg_get_annotation_from_id($annotationGuid);

if (elgg_instanceof($page, 'object', 'page') || elgg_instanceof($page, 'object', 'page_top')) {
	// only allow owners and admin to restore
	if (elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == $page->getOwnerGuid()|| check_entity_relationship(elgg_get_logged_in_user_guid(), "group_admin", $page->container_guid)) {
		$container = get_entity($page->container_guid);
		$page->description = $annotation->value;
		
if ($page->save()) {

	//check in the page becaused the user just saved it
	if($page->deleteMetadata("checkedOut")){
		system_message(elgg_echo('pages:checked_in'));
	}
	else{
		system_message('Page could not be checked in. It is still locked for editing');
	}
	elgg_clear_sticky_form('page');

	// Now save description as an annotation
	$page->annotate('page', $page->description, $page->access_id);

	system_message(elgg_echo('pages:saved'));

	if ($new_page) {
		add_to_river('river/object/page/create', 'create', elgg_get_logged_in_user_guid(), $page->guid);
	}

	forward($page->getURL());
} else {
	register_error(elgg_echo('pages:error:notsaved'));
	forward(REFERER);
}



	}
}

register_error(elgg_echo('pages:restore:failure'));
forward(REFERER);
