<?php

/* * **********************************************************************
 * Project Resigtry Plugin
 * ********************************************************************** */

elgg_register_event_handler('init', 'system', 'project_registry_init');

/**
 * Initialize the project registry plug-in
 */
function project_registry_init() {

    // register a library of helper functions
    elgg_register_library('elgg:project_registry', elgg_get_plugins_path() . 'project_registry/lib/project_registry.php');

    //register entity type for search
    elgg_register_entity_type('object', 'project_registry');

    // Set up the menu
    $item = new ElggMenuItem('project_registry', elgg_echo('projects'), 'projects');
    elgg_register_menu_item('site', $item);

    // register a project handler, so we can have nice URLs
    elgg_register_page_handler('projects', 'projects_page_handler');

    // Register URL handlers for projects
    elgg_register_entity_url_handler('object', 'project_registry', 'projects_url');

    // Register some actions
    //$action_base = elgg_get_plugins_path() . 'project_registry/actions/project_registry';
    //elgg_register_action("projects/edit", "$action_base/edit.php");
    //elgg_register_action("projects/delete", "$action_base/delete.php");
    //elgg_register_action("requests/add", "$action_base/add.php");
}

/**
 * Override the project url
 *
 * @param ElggObject $entity Page object
 * @return string
 */
function projects_url($entity) {
    $title = elgg_get_friendly_title($entity->title);

    return "projects/view/{$entity->guid}/$title";
}

/**
 *
 * @param array $project
 * @return bool
 */
function projects_page_handler($project) {

    if (!isset($project[0])) {
        $project[0] = 'all';
    }

    elgg_push_breadcrumb(elgg_echo('projects'), 'projects');

    $base_dir    = elgg_get_plugins_path() . 'project_registry/pages/project_registry';
    $angular_dir = elgg_get_plugins_path() . 'project_registry/js/views';

    switch ($project[0]) {
        case 'all':
            include "$base_dir/all.php";
            break;
        case 'list':
            include "$angular_dir/list.php";
            break;
        case 'add':
            include "$angular_dir/add.php";
            break;
        case 'view':
            include "$angular_dir/view.php";
            break;
        case 'edit':
            include "$angular_dir/edit.php";
            break;
        case 'sidebar':
            include "$angular_dir/sidebar.php";
            break;
        case 'toc':
            include "$angular_dir/toc.php";
            break;
        case 'messages':
            include "$angular_dir/messages.php";
            break;
        case 'add_admin':
            include "$angular_dir/add_admin.php";
            break;
        case 'manage_admins':
            include "$angular_dir/manage_admins.php";
            break;
        case 'dashboard':
            include "$angular_dir/dashboard.php";
            break;
        default:
            return false;
    }

    return true;
}
