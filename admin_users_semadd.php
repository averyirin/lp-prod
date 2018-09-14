<?php
/**
 * Display an add user form.
 */

$title = elgg_echo('semadd');
$body = elgg_view_form('semadd', array(), array('show_admin' => true));

echo elgg_view_module('inline', $title, $body);