<?php

?>
<div class='template-header'>
    <h2 class='template-heading'><?php echo elgg_echo('register');?></h2>
</div>

<div class="row">
    <div class="col-md-8">
		
        <form name='userForm' ng-submit="vm.registerUser(userForm.$valid)" class="angular-form" ng-focus-error="" scroll-to-error novalidate>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('name'); ?></label>
                </div>

                <div class='col-sm-8'>
                    <input type='text' class='' name='name' ng-model='vm.user.name' ng-minlength=3 ng-maxlength=200 required/>

                    <div ng-messages="userForm.name.$error" ng-if="(userForm.title.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                    </div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('email'); ?></label>
                    <p class='form-hint'><?php echo elgg_echo('register:emailRules'); ?></p>
                </div>

                <div class='col-sm-8'>
                    <input type='email' class='' name='email' ng-model='vm.user.email' email-validation required/>
                    
                    <div ng-messages="userForm.email.$error" ng-if="(userForm.email.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                        <p class="error" ng-show="userForm.email.$error.emailValidation"><?php echo elgg_echo('register:emailRules'); ?></p>
                    </div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('register:emailAgain'); ?></label>
                </div>

                <div class='col-sm-8'>
                    <input type='email' class='' name='email_verification' ng-model='vm.email_verification' required/>
                    
                    <div ng-messages="userForm.email_verification.$error" ng-if="(userForm.email_verification.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                        <p class="error" ng-show="vm.user.email != vm.email_verification"><?php echo elgg_echo('register:emailMismatch'); ?></p>
                    </div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('username'); ?></label>
                    <p class='form-hint'><?php echo elgg_echo('register:username:notice'); ?></p>
                </div>

                <div class='col-sm-8'>
                    <input type='text' class='' name='username' ng-model='vm.user.username' required/>

                    <div ng-messages="userForm.username.$error" ng-if="(userForm.title.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                    </div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('password'); ?></label>
                    <p class='form-hint'><?php echo elgg_echo('register:pswdRules'); ?></p>
                </div>

                <div class='col-sm-8'>
                    <input type='password' class='' name='password' ng-model='vm.user.password' password-validation required/>

                    <div ng-messages="userForm.password.$error" ng-if="(userForm.password.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                        <p class="error" ng-show="userForm.password.$error.passwordValidation"><?php echo elgg_echo('register:pswdInvld'); ?></p>
                    </div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-sm-4">
                    <label><?php echo elgg_echo('passwordagain'); ?></label>
                </div>

                <div class='col-sm-8'>
                    <input type='password' class='' name='password_verification' ng-model='vm.password_verification' required/>
                    
                    <div ng-messages="userForm.password_verification.$error" ng-if="(userForm.password_verification.$touched) || (userForm.$submitted)">
                        <div ng-messages-include="users/messages"></div>
                        
                        <p class="error" ng-show="vm.user.password != vm.password_verification"><?php echo elgg_echo('RegistrationException:PasswordMismatch'); ?></p>
                    </div>
                </div>
            </div>
			
            <button type='submit' class='elgg-button elgg-button-action'><?php echo elgg_echo('register');?></button>
        </form>
		
    </div>
</div>