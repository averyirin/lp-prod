<?php
/**
 * Elgg add user form.
 *
 * @package Elgg
 * @subpackage Core
 * 
 */

$name = $svc = $admin = '';

if (elgg_is_sticky_form('semadd')) {
	extract(elgg_get_sticky_values('semadd'));
	elgg_clear_sticky_form('semadd');
	if (is_array($admin)) {
		$admin = $admin[0];
	}
}

?>
<div>
	<label><?php echo elgg_echo('semname');?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'name',
		'value' => $name,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('svc'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'svc',
		'value' => $svc,
	));
	?>
</div></div>

<div class="elgg-foot">
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('register'))); ?>
</div>