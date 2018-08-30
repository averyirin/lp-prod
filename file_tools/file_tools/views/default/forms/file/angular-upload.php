<?php
$siteUrl = elgg_get_site_url();

$container_entity = elgg_get_page_owner_entity();
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);

if(elgg_instanceof($container_entity, "group")){
    $isGroup = true;
	if($access_id = -1){
		$access_id = $container_entity->group_acl;
	}
}
?>

<div ng-cloak ng-if="vm.errorMessage">
    <div ng-repeat="message in vm.messages">
        <div class='alert alert-danger fade'>
            <strong>Error:</strong> {{message}}
        </div>
    </div>
</div>

<form id="file-tools-single-form" class="angular-form" name="fileForm" ng-submit="vm.uploadFile(fileForm.$valid)" ng-focus-error="" novalidate>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('file:file') ?></label>
        </div>
        <div class="col-sm-12">
            <input type="file" ngf-select="" ng-model="vm.file.file" name="file" required />
            <div ng-show="(fileForm.file.$touched) || (fileForm.$submitted)">
                <p class="error" ng-show="fileForm.file.$error.required"><?php echo elgg_echo('file:nofile'); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('title') ?></label>
        </div>
        <div class="col-sm-12">
            <input type="text" ng-model="vm.file.title" name="title">
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('description') ?></label>
        </div>
        <div class="col-sm-12">
            <textarea class="elgg-input-longtext" ng-model="vm.file.description" name="description"></textarea>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('tags') ?></label>
        </div>
        <div class="col-sm-12">
            <input type="text" ng-model="vm.file.tags" name="tags">
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('file_tools:forms:edit:parent') ?></label>
        </div>
        <div class="col-sm-12">
           	<?php
            	$parent_guid = 0;
                if($file = elgg_extract("entity", $vars)){
                    if($folders = $file->getEntitiesFromRelationship(FILE_TOOLS_RELATIONSHIP, true, 1)){
                        $parent_guid = $folders[0]->getGUID();
                    }
                }
                echo elgg_view("input/folder_select", array("name" => "folder_guid", "ng-model" => "vm.file.folder_id"));
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('categories') ?></label>
        </div>
        <div class="col-sm-12">
            <?php
                echo elgg_view('input/categories', $vars);
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <label><?php echo elgg_echo('access') ?></label>
        </div>
        <div class="col-sm-12">
            <?php
            if($isGroup){
                echo elgg_view('input/access', array('name' => 'access_id', 'ng-model' => 'vm.file.access_id', 'admin_option' => array($container_entity->group_admin_acl => "Group Admins: ".$container_entity->name)));
            }
            else{
                echo elgg_view('input/access', array('name' => 'access_id', 'ng-model' => 'vm.file.access_id'));
            }
            ?>
        </div>
    </div> 
   
    <button type='submit' class='elgg-button elgg-button-action'><?php echo elgg_echo('upload');?></button>
</form>

<div ng-cloak ng-show='vm.isLoading'>
    <div class='full-screen loading-screen'>
        <div class="overlay-container">
            <h3 class='overlay-message'>{{vm.file.file.progress}}%</h3>
            <div class="progress-bar-container">
                <div class="progress-bar" class="ng-binding"></div>
            </div>
            <h4 ng-if="vm.uploading" class='overlay-message'><?php echo elgg_echo('file:uploading');?></h4>
            <h4 ng-if="vm.saving" class='overlay-message'><?php echo elgg_echo('file:uploadComplete');?></h4>
        </div>
    </div>
</div>

