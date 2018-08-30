<?php
/**
 * Project name-based search form body
 */

$name_string = elgg_echo('projects:search:name');

$params = array(
	'name' => 'tag',
	'class' => 'elgg-input-search mbm',
	'value' => $name_string,
	'onclick' => "if (this.value=='$name_string') { this.value='' }",
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search:go')));