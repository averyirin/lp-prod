<?php
/**
 * View a revision of page
 *
 * @package ElggPages
 */


$id = get_input('id');
$annotation = elgg_get_annotation_from_id($id);
if (!$annotation) {
	forward();
}

$page = get_entity($annotation->entity_guid);
if (!$page) {
	
}

elgg_set_page_owner_guid($page->getContainerGUID());

group_gatekeeper();

$container = elgg_get_page_owner_entity();
if (!$container) {
}

$title = $page->title . ": " . elgg_echo('pages:revision');

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($container->name, "pages/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($container->name, "pages/owner/$container->username");
}

pages_prepare_parent_breadcrumbs($page);
elgg_push_breadcrumb($page->title, $page->getURL());
elgg_push_breadcrumb(elgg_echo('pages:revision'));


// can add subpage if can edit this page and write to container (such as a group)
if ($page->canEdit()) {
	$url = "action/annotations/page/restore";
	$urlParam = "?page=".$annotation->entity_guid."&annotation=".$id;
	$url.=$urlParam;
	elgg_register_menu_item('title', array(
			'name' => 'restore',
			'href' => $url,
			'is_action' => true,
			'text' => elgg_echo('pages:restore'),
			'link_class' => 'elgg-button elgg-button-action',
	));
}

$content = elgg_view('object/page_top', array(
	'entity' => $page,
	'revision' => $annotation,
	'full_view' => true,
));


$sidebar = elgg_view('pages/sidebar/history', array('page' => $page));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
