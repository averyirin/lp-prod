<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 */

$password = $password2 = '';
$username = get_input('u');
$email = get_input('e');
$name = get_input('n');

if (elgg_is_sticky_form('register')) {
	extract(elgg_get_sticky_values('register'));
	elgg_clear_sticky_form('register');
}

?>
<div class="mtm">
	<label><?php echo elgg_echo('name'); ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'name',
		'value' => $name,
		'class' => 'elgg-autofocus text-input',
	));
	?>
</div>
<div class="clearfix">
	<label><?php echo elgg_echo('email'); ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'email',
		'value' => $email,
		'class' => "text-input"
	));
	?>
	<span class="form_hint"><?php echo elgg_echo('register:emailRules'); ?></span>
</div>
<div>
	<label><?php echo elgg_echo('register:emailAgain'); ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'email2',
		'value' => $email2,
		'class' => "text-input"
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('username'); ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'username',
		'value' => $username,
		'class' => "text-input"
	));
	?>
	<span class="form_hint"><?php echo elgg_echo('register:username:notice'); ?></span>
</div>
<div class="clearfix">
	<label><?php echo elgg_echo('password'); ?></label>
	<?php
	echo elgg_view('input/password', array(
		'name' => 'password',
		'class' => "text-input"
	));
	?>
	<span class="form_hint"><?php echo elgg_echo('register:pswdRules'); ?></span>
</div>
<div>
	<label><?php echo elgg_echo('passwordagain'); ?></label>
	<?php
	echo elgg_view('input/password', array(
		'name' => 'password2',
		'class' => "text-input"
	));
	?>
</div>

<?php
// view to extend to add more fields to the registration form
echo elgg_view('register/extend', $vars);

// Add captcha hook
echo elgg_view('input/captcha', $vars);

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register')));
echo '</div>';
