<?php 
$name = get_input('name');
$email = get_input('email');

$content = "<h1 class='elgg-heading-walledgarden'>". elgg_echo('register:confirmationHeading', array($name)) ."</h1>".
	"<p>". elgg_echo('register:confirmationBody', array($email)) ."</p>".
	"<h3 class='link'><a href='".elgg_get_site_url()."'>Return Home</a></h3>";
$title= "";
$body = elgg_view_layout('walled_garden', array('content' => $content));
echo elgg_view_page($title, $body, 'walled_garden');