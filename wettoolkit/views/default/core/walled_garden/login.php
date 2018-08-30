<?php
/**
 * Walled garden login
 */

$title = elgg_get_site_entity()->name;
$notice = elgg_echo('wet:notice');
$welcome = elgg_echo('wet:welcome');
$warning = elgg_echo('wet:warning');
$warning_text = elgg_echo('wet:warning_text');

$menu = elgg_view_menu('walled_garden', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-general elgg-menu-hz',
));

$login_box = elgg_view('core/account/login_box', array('module' => 'walledgarden-login'));

echo <<<HTML
<!-- <div class="elgg-col elgg-col-2of2">
	<div class="elgg-inner">
		<div class="alert alert-warning">$notice</div>
	</div>
</div> -->
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		<h1 class="elgg-heading-walledgarden">
			$welcome
		</h1>
		$menu
	</div>
	<div class='warning-message'>
		<h3>$warning</h3>
		<h4>$warning_text</h4>
	</div>
</div>
<div class="elgg-col elgg-col-1of2">
	<div class="elgg-inner">
		$login_box
	</div>
</div>
HTML;
