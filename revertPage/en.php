<?php
/**
 * Pages languages
 *
 * @package ElggPages
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'pages:restore' => 'Revert page',

	'pages' => "Pages",
	'pages:owner' => "%s's pages",
	'pages:friends' => "Friends' pages",
	'pages:all' => "All site pages",
	'pages:add' => "Add a page",

	'pages:group' => "Group pages",
	'groups:enablepages' => 'Enable group pages',

	'pages:edit' => "Edit this page",
	'pages:delete' => "Delete this page",
	'pages:history' => "History",
	'pages:view' => "View page",
	'pages:revision' => "Revision",
	'pages:current_revision' => "Current Revision",
	'pages:revert' => "Revert",

	'pages:navigation' => "Navigation",
	'pages:new' => "A new page",
	'pages:notification' =>
'%s added a new page:

%s
%s

View and comment on the new page:
%s
',
	'item:object:page_top' => 'Top-level pages',
	'item:object:page' => 'Pages',
	'pages:nogroup' => 'This group does not have any pages yet',
	'pages:more' => 'More pages',
	'pages:none' => 'No pages created yet',

	/**
	* River
	**/

	'river:create:object:page' => '%s created a page %s',
	'river:create:object:page_top' => '%s created a page %s',
	'river:update:object:page' => '%s updated a page %s',
	'river:update:object:page_top' => '%s updated a page %s',
	'river:comment:object:page' => '%s commented on a page titled %s',
	'river:comment:object:page_top' => '%s commented on a page titled %s',

	/**
	 * Form fields
	 */

	'pages:title' => 'Page title',
	'pages:description' => 'Page text',
	'pages:tags' => 'Tags',
	'pages:parent_guid' => 'Parent page',
	'pages:access_id' => 'Read access',
	'pages:write_access_id' => 'Write access',

	/**
	 * Status and error messages
	 */
	'pages:noaccess' => 'No access to page',
	'pages:cantedit' => 'You cannot edit this page',
	'pages:saved' => 'Page saved',
	'pages:notsaved' => 'Page could not be saved',
	'pages:error:no_title' => 'You must specify a title for this page.',
	'pages:delete:success' => 'The page was successfully deleted.',
	'pages:delete:failure' => 'The page could not be deleted.',
	'pages:revision:delete:success' => 'The page revision was successfully deleted.',
	'pages:revision:delete:failure' => 'The page revision could not be deleted.',
	'pages:revision:not_found' => 'Cannot find this revision.',

	/**
	 * Page
	 */
	'pages:strapline' => 'Last updated %s by %s',

	/**
	 * History
	 */
	'pages:revision:subtitle' => 'Revision created %s by %s',

	/**
	 * Widget
	 **/

	'pages:num' => 'Number of pages to display',
	'pages:widget:description' => "This is a list of your pages.",

	/**
	 * Submenu items
	 */
	'pages:label:view' => "View page",
	'pages:label:edit' => "Edit page",
	'pages:label:history' => "Page history",

	/**
	 * Sidebar items
	 */
	'pages:sidebar:this' => "This page",
	'pages:sidebar:children' => "Sub-pages",
	'pages:sidebar:parent' => "Parent",

	'pages:newchild' => "Create a sub-page",
	'pages:checkout' => "Checkout",
	'pages:checkin' => "Check In",
	'pages:backtoparent' => "Back to '%s'",

	'pages:checked_out_by' => "Page is currently checked out by: %s",
	'pages:dialog_text' => "You can now check out and check in pages. When editing a page, it will be checked out under your account.
		  					This means it will be locked from editing by any other user. To check the page back in and unlock it for editing, simply save your changes.",
	'pages:checked_out' => 'You have checked out this page. Save your changes to check it back in.',
	'pages:checked_in' => 'Page has been checked in',

);

add_translation("en", $english);
