<?php 

	gatekeeper();

	$page_owner = elgg_get_page_owner_entity();
	
	if(!empty($page_owner))	{
		// build breadcrumb
		elgg_push_breadcrumb(elgg_echo("file"), "file/all");
		if(elgg_instanceof($page_owner, "group", null, "ElggGroup")){
			elgg_push_breadcrumb($page_owner->name, "file/group/" . $page_owner->getGUID() . "/all");
		} else {
			elgg_push_breadcrumb($page_owner->name, "file/owner/" . $page_owner->username);
		}
		elgg_push_breadcrumb(elgg_echo("file:upload"));
		
		// get data
		$upload_type = get_input("upload_type", "single");
		
		// build page elements
		$title_text = elgg_echo("file:upload");
		
		// body
		$form_vars = array(
			"enctype" => "multipart/form-data",
			"class" => "hidden"
		);
		$body_vars = array();

		$multi_vars = $form_vars;
		$multi_vars["id"] = "file-tools-multi-form";
		$multi_vars["action"] = "action/file/upload";
		$zip_vars = $form_vars;
		$zip_vars["id"] = "file-tools-zip-form";
		$single_vars = $form_vars;
		$single_vars["id"] = "file-tools-single-form";
        //check for older browser. Make angular app if not older browser
        $ua = getBrowser();
        $ie9 = false;
        if($ua['name'] == 'Internet Explorer' && $ua['version'] == 9.0){
            $ie9 = true;
        }
        if(!$ie9){
            $single_vars["action"] = false;
            $single_vars["name"] = "fileForm";
            $single_vars["ng-submit"] = "vm.uploadFile(fileForm.\$valid)";
        }
		
		switch ($upload_type) {
			case "multi":
				unset($multi_vars["class"]);
				
				break;
			case "zip":
				unset($zip_vars["class"]);
				
				break;
			default:
				elgg_load_library("elgg:file");
				
				$body_vars = file_prepare_form_vars();
				
				unset($single_vars["class"]);
				break;
		}
		
		// build different forms, and include angular resources
		$siteUrl = elgg_get_site_url();
		$pluginUrl = $siteUrl."mod/file_tools";
		$wettoolkit_url = $siteUrl."mod/wettoolkit";
		$body = "<section ng-app='portal'>";
        $body .= "<div ng-view">
		$body .= "<div id='file-tools-upload-wrapper' ng-controller='FileCtrl as vm'>";
        if($ie9){
            $body .= elgg_view_form("file/upload", $single_vars, $body_vars);
        }
        else{
            $body .= elgg_view("forms/file/angular-upload", $single_vars, $body_vars);
        }
		$body .= elgg_view_form("file_tools/upload/multi", $multi_vars);
		$body .= elgg_view_form("file_tools/upload/zip", $zip_vars);
		$body .= "</div>
                </div>
		        </section>
				<script src='/portal/node_modules/angular/angular.min.js'></script>
				<script src='/portal/node_modules/angular-resource/angular-resource.min.js'></script>
				<script src='/portal/node_modules/angular-route/angular-route.min.js'></script>
				<script src='/portal/node_modules/angular-messages/angular-messages.min.js'></script>
				<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload-shim.min.js'></script>
				<script src='/portal/node_modules/ng-file-upload/dist/ng-file-upload.min.js'></script>
				<script src='/portal/node_modules/angular-animate/angular-animate.min.js'></script>
				<script src='/portal/node_modules/angular-ui-slider/src/slider.js'></script>
				<script src='/portal/node_modules/angular-ui-bootstrap/dist/ui-bootstrap.min.js'></script>
				<script src='/portal/node_modules/crypto-js/crypto-js.js'></script>
				<script src='$pluginUrl/js/app_v2_2.js'></script>
				<script src='$wettoolkit_url/js/controllers/FileController_v2_2.js'></script>
				<script src='$wettoolkit_url/js/directives/FileDirective_v2_2.js'></script>
				<script src='$wettoolkit_url/js/services/FileService_v2_2.js'></script>
                <script src='$wettoolkit_url/js/services/UserService_v2_2.js'></script>
				<script src='$wettoolkit_url/js/services/NotificationService_v2_2.js'></script>
                <script src='$wettoolkit_url/js/services/EntityService_v2_2.js'></script>";
				
		$tabs = elgg_view("file_tools/upload_tabs", array("upload_type" => $upload_type));
		
		// build page
		$page_data = elgg_view_layout("content", array(
			"title" => $title_text,
			"content" => $body,
			"filter" => $tabs
		));
		
		// draw page
		echo elgg_view_page($title_text, $page_data);
	} else {
		forward();
	}