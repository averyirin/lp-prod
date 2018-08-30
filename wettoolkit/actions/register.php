<?php
/**
 * Elgg registration action
 *
 * @package Elgg.Core
 * @subpackage User.Account
 */

elgg_make_sticky_form('register');
// Get variables
$username = get_input('username');
$password = get_input('password', null, false);
$password2 = get_input('password2', null, false);
$email = get_input('email');
$email2 = get_input('email2');
$name = get_input('name');
$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

if (elgg_get_config('allow_registration')) {
	try {
		$approvedDomains = array('forces.gc.ca', 'test.gc.ca');

		if (strcmp($email, $email2) != 0) {
			throw new RegistrationException(elgg_echo('register:emailMismatch'));
		}

		if (trim($password) == "" || trim($password2) == "") {
			throw new RegistrationException(elgg_echo('RegistrationException:EmptyPassword'));
		}

		if (strcmp($password, $password2) != 0) {
			throw new RegistrationException(elgg_echo('RegistrationException:PasswordMismatch'));
		}

		//check for valid email domain
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$domain = array_pop(explode('@',$email));

			if(!in_array($domain, $approvedDomains)) {
				throw new RegistrationException(elgg_echo('register:emailRules'));
			}
		}

		//check for bad password
		if(strlen($password) < 8 || preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/', $password) == 0){
			throw new RegistrationException(elgg_echo('register:pswdInvld'));
		} 

		$guid = register_user($username, $password, $name, $email, false, $friend_guid, $invitecode);

		if ($guid) {
			$new_user = get_entity($guid);

			// allow plugins to respond to self registration
			// note: To catch all new users, even those created by an admin,
			// register for the create, user event instead.
			// only passing vars that aren't in ElggUser.
			$params = array(
				'user' => $new_user,
				'password' => $password,
				'friend_guid' => $friend_guid,
				'invitecode' => $invitecode
			);

			// @todo should registration be allowed no matter what the plugins return?
			if (!elgg_trigger_plugin_hook('register', 'user', $params, TRUE)) {
				$ia = elgg_set_ignore_access(true);
				$new_user->delete();
				elgg_set_ignore_access($ia);
				// @todo this is a generic messages. We could have plugins
				// throw a RegistrationException, but that is very odd
				// for the plugin hooks system.
				throw new RegistrationException(elgg_echo('registerbad'));
			}

			//save registered users permanent forces.gc.ca email
			$new_user->registeredEmail = $email;

			elgg_clear_sticky_form('register');
			system_message(elgg_echo("registerok", array(elgg_get_site_entity()->name)));

			// if exception thrown, this probably means there is a validation
			// plugin that has disabled the user
			try {
				login($new_user);
			} catch (LoginException $e) {
				// do nothing
			}

			// Forward on success, assume everything else is an error...
			forward(elgg_get_site_url()."users/registered/".$name."/".$email);
		} else {
			register_error(elgg_echo("registerbad"));
		}
	} catch (RegistrationException $r) {
		register_error($r->getMessage());
	}
} else {
	register_error(elgg_echo('registerdisabled'));
}

forward(REFERER);
