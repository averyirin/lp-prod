<?php
/**
 * All files
 *
 */

$title = elgg_echo('register');
$siteUrl = elgg_get_site_url();
$wettoolkit_url = $siteUrl."mod/wettoolkit";
$waitText = elgg_echo('overlay:wait');

$content = 
"
<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css'/>
<script type='text/javascript' src='https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js'></script>
<script src='/portal/node_modules/angular/angular.min.js'></script>
<script src='/portal/node_modules/angular-resource/angular-resource.min.js'></script>
<script src='/portal/node_modules/angular-route/angular-route.min.js'></script>
<script src='/portal/node_modules/angular-ui-router/release/angular-ui-router.min.js'></script>
<script src='/portal/node_modules/angular-messages/angular-messages.min.js'></script>
<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload-shim.min.js'></script>
<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload.min.js'></script>
<script src='/portal/node_modules/angular-animate/angular-animate.min.js'></script>
<script src='/portal/node_modules/angular-ui-slider/src/slider.js'></script>
<script src='/portal/node_modules/angular-ui-bootstrap/dist/ui-bootstrap.min.js'></script>
<script type='text/javascript' src='$wettoolkit_url/dist/js/angular-datatables.min.js'></script>
<script src='$wettoolkit_url/js/app_v2_2.js'></script>
<script src='$wettoolkit_url/js/controllers/UserController_v2_2.js'></script>
<script src='$wettoolkit_url/js/controllers/UsersController_v2_2.js'></script>
<script src='$wettoolkit_url/js/directives/AppDirective_v2.js'></script>
<script src='$wettoolkit_url/js/directives/ProjectDirective_v2_2.js'></script>
<script src='$wettoolkit_url/js/services/ProjectService_v2_2.js'></script>
<script src='$wettoolkit_url/js/services/UserService_v2_2.js'></script>
<script src='$wettoolkit_url/js/services/NotificationService_v2_2.js'></script>

<section id='ng-app' ng-app='portal' style='position:relative;'>
	
	<div class='alert alert-success fade' ng-cloak ng-show='successMessage'>
		<strong>Success:</strong>{{message}}
	</div>
        
	<div id='error-message' class='alert alert-danger fade' ng-cloak ng-show='errorMessage'>
		<strong>Error:</strong>{{message}}
	</div>
	
	<div class='fade' ng-cloak ng-view>
	</div>
	
	<div ng-cloak ng-if='isLoading'>
		<div class='full-screen loading-screen'>
			<h3 class='overlay-message'>$waitText</h3>
		</div>
	</div>
	
</section>";


$body = elgg_view_layout('walled_garden', array(
	'content' => $content,
	'title' => null,
	'sidebar' => null,
));

echo elgg_view_page($title, $body, 'walled_garden');
