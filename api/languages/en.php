<?php
/**
 * Elgg Web Services language pack.
 * 
 * @package Webservice
 * @author Saket Saurabh
 */

$english = array(
	'web_services:user' => "User", 
	'web_services:blog' => "Blog", 
	'web_services:wire' => "Wire", 
	'web_services:core' => "Core", 
	'web_services:group' => "Group",
	'web_services:file' => "File",
	'web_services:messages' => "Messages",
	'web_services:settings_description' => "Select the web services below that you wish to be enabled:",
	'web_services:selectfeatures' => "Select the features to be enabled",
	'friends:alreadyadded' => "%s is already added as friend",
	'friends:remove:notfriend' => "%s is not your friend",
	'blog:message:notauthorized' => "Not authorized to carry this request",
	'blog:message:noposts' => "No blog posts by user",

	'admin:utilities:web_services' => 'Web Services Tests',
	'web_services:tests:instructions' => 'Run the unit tests for the web services plugin',
	'web_services:tests:run' => 'Run tests',
	'web_services:likes' => 'Likes',
	'likes:notallowed' => 'Not allowed to like',
	
	//A resolution to json convertion error (for river)
	'river:update:user:default' => ' updated their profile ',

	//Project Operation Email Notification
	'email:project:submit:projectNotFound' => "The project is not in the system",
	'email:project:submit:heading' => "A project request has been successfully submitted",
	'email:project:submit:body' => "Good Day %s,

A project request has been submitted by %s. 
Project title: %s

To review it, click on the link below:

%s

If you cannot click on the link, copy and paste it into your browser manually.

%s
%s",
	'email:project:submit:error' => "There was an error sending your project submission email",
);
				
add_translation("en", $english);
