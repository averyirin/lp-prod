<?php
/**
 * All files
 *
 */

$title = elgg_echo('projects');
$siteUrl = elgg_get_site_url();
$pluginUrl = $siteUrl."mod/project_registry";
$wettoolkit_url = $siteUrl."mod/wettoolkit";
$content = 
"
<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.css'/>
<script type='text/javascript' src='https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js'></script>
<script src='/portal/node_modules/angular/angular.min.js'></script>
<script src='/portal/node_modules/angular-resource/angular-resource.min.js'></script>
<script src='/portal/node_modules/angular-route/angular-route.min.js'></script>
<script src='/portal/node_modules/angular-messages/angular-messages.min.js'></script>
<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload-shim.min.js'></script>
<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload.min.js'></script>
<script src='/portal/node_modules/angular-animate/angular-animate.min.js'></script>
<script src='/portal/node_modules/angular-ui-slider/src/slider.js'></script>
<script src='/portal/node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.min.js'></script>
<script type='text/javascript' src='$wettoolkit_url/dist/js/angular-datatables.min.js'></script>
<script src='/portal/node_modules/crypto-js/crypto-js.js'></script>
<script src='$pluginUrl/js/app.js'></script>
<script src='$wettoolkit_url/js/controllers/MainController.js'></script>
<script src='$wettoolkit_url/js/controllers/ProjectController.js'></script>
<script src='$wettoolkit_url/js/controllers/ProjectDashboardController.js'></script>
<script src='$wettoolkit_url/js/controllers/UserController.js'></script>
<script src='$wettoolkit_url/js/controllers/UsersController.js'></script>
<script src='$wettoolkit_url/js/directives/AppDirective.js'></script>
<script src='$wettoolkit_url/js/services/ProjectService.js'></script>
<script src='$wettoolkit_url/js/services/UserService.js'></script>
<script src='$wettoolkit_url/js/services/NotificationService.js'></script>
<script src=$siteUrlmod'mod/tinymce_four/vendor/tinymce/jscripts/tiny_mce/tinymce.js'></script>

<section ng-app='portal' ng-controller='MainCtrl' style='position:relative;'>
	<link rel='stylesheet' href='mod/project_registry/css/styles.css'/>
	
	<div class='alert alert-success fade' ng-cloak ng-show='successMessage'>
		<strong>Success:</strong>{{message}}
	</div>
	
	<div class='fade' ng-cloak ng-view>
	</div>
    
	<div ng-cloak ng-if='isViewLoading'>
		<div class='full-screen loading-screen'>
			<h3>Please Wait...</h3>
		</div>
	</div>
    
    <div ng-cloak ng-if='isLoading'>
		<div class='full-screen loading-screen'>
			<h3>Please Wait...</h3>
		</div>
	</div>
    
</section>";

$sidebar = elgg_view('project_registry/sidebar/filter');
$sidebar .= elgg_view('project_registry/sidebar/find');

switch ($vars['page']) {
	case 'submitted':

		break;
	case 'underreview':

		break;
	case 'inprogress':

		break;
	case 'completed':

		break;
	case 'onhold':
	
		break;
	default:
		break;
}

$body = elgg_view_layout('one_column', array(
	'content' => $content,
	'title' => null,
	'sidebar' => null,
	'filter_override' => elgg_view('project_registry/nav', array('selected' => $vars['page'])),
));

echo elgg_view_page($title, $body);