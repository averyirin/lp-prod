<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$releasedAccessId = elgg_extract('releasedAccessId', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
if($releasedAccessId){
	$access_id = $releasedAccessId;
}

$container_guid = elgg_extract('container_guid', $vars);
$container_entity = get_entity($container_guid);
$groupFile = false;

if(elgg_instanceof($container_entity, "group")){
	if($access_id = -1){
		$access_id = $container_entity->group_acl;
	}
	$groupFile = true;
}

$isTimeReleased = elgg_extract('isTimeReleased', $vars);
$releaseDate = '';
$releaseTime = '';
if($isTimeReleased){
	$isTimeReleased = 1;
	$releaseDate = elgg_extract('releaseDate', $vars);
	$releaseTime = elgg_extract('releaseTime', $vars);
}
else{
	$isTimeReleased = 0;
}

if(elgg_extract('isClosable', $vars)){
	$isClosable = 1;
	$closeDate = elgg_extract('closeDate', $vars);
	$closeTime = elgg_extract('closeTime', $vars);
}
else{
	$isClosable = 0;
}

elgg_load_css("jquery.uploadify");
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

?>
<div>
	<label><?php echo $file_label; ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div class="date-time-container">
	<label><?php echo elgg_echo('file_tools:time_release'); ?></label>
	<?php echo elgg_view('input/dropdown', array('name' => 'released', 'value'=> $isTimeReleased, 'class'=>'time-toggle', 'options_values' => array('No', 'Yes'))); ?>
	<?php if($isTimeReleased){ ?>
	<div class="time-release active">
	<?php }else{ ?>
	<div class="time-release">
	<?php }
		
		$time = array("00:00" => "00:00", "01:00" => "01:00", "02:00" => "02:00", "03:00" => "03:00", "04:00"=>"04:00", "05:00"=>"05:00", "06:00"=>"06:00", "07:00"=>"07:00", "08:00"=>"08:00", "09:00"=>"09:00", "10:00"=>"10:00", "11:00"=>"11:00", "12:00"=>"12:00", "13:00"=>"13:00", "14:00"=>"14:00",
			"15:00"=>"15:00", "16:00"=>"16:00", "17:00"=>"17:00", "18:00"=>"18:00", "19:00"=>"19:00", "20:00"=>"20:00", "21:00"=>"21:00", "22:00"=>"22:00", "23:00"=>"23:00");
		?>
		<label><?php echo elgg_echo('file_tools:date'); ?></label>
		<?php echo elgg_view('input/datepicker', array('name' => 'release_date', 'class'=>'release_date', 'value'=>$releaseDate)); ?>
		<label><?php echo elgg_echo('file_tools:time'); ?></label>
		<?php echo elgg_view('input/dropdown', array('name' => 'release_time', 'options_values' => $time, 'value'=>$releaseTime)); ?>
	</div>
</div>
<div class="date-time-container">
	<label><?php echo elgg_echo('file_tools:time_delete'); ?></label>
	<?php echo elgg_view('input/dropdown', array('name' => 'closed', 'value'=> $isClosable, 'class'=>'time-toggle', 'options_values' => array('No', 'Yes'))); ?>
	<?php if($isClosable){ ?>
	<div class="time-release active">
	<?php }else{ ?>
	<div class="time-release">
	<?php }
		
		$time = array("00:00" => "00:00", "01:00" => "01:00", "02:00" => "02:00", "03:00" => "03:00", "04:00"=>"04:00", "05:00"=>"05:00", "06:00"=>"06:00", "07:00"=>"07:00", "08:00"=>"08:00", "09:00"=>"09:00", "10:00"=>"10:00", "11:00"=>"11:00", "12:00"=>"12:00", "13:00"=>"13:00", "14:00"=>"14:00",
			"15:00"=>"15:00", "16:00"=>"16:00", "17:00"=>"17:00", "18:00"=>"18:00", "19:00"=>"19:00", "20:00"=>"20:00", "21:00"=>"21:00", "22:00"=>"22:00", "23:00"=>"23:00");
		?>
		<label><?php echo elgg_echo('file_tools:dateClosed'); ?></label>
		<?php echo elgg_view('input/datepicker', array('name' => 'close_date', 'class'=>'release_date', 'value'=>$closeDate)); ?>
		<label><?php echo elgg_echo('file_tools:timeClosed'); ?></label>
		<?php echo elgg_view('input/dropdown', array('name' => 'close_time', 'options_values' => $time, 'value'=>$closeTime)); ?>
	</div>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>

<?php
if(file_tools_use_folder_structure()){
	$parent_guid = 0;
	if($file = elgg_extract("entity", $vars)){
		if($folders = $file->getEntitiesFromRelationship(FILE_TOOLS_RELATIONSHIP, true, 1)){
			$parent_guid = $folders[0]->getGUID();
		}
	}
	?>
	<div>
		<label><?php echo elgg_echo("file_tools:forms:edit:parent"); ?><br />
		<?php
			echo elgg_view("input/folder_select", array("name" => "folder_guid", "value" => $parent_guid));		
		?>
		</label>
	</div>
<?php 
}

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
	<label><?php echo elgg_echo('access'); ?><span class="release-info">If a time released file, this will be the access level upon release.</span></label><br />
	<?php
	if($groupFile){
		echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id, 'admin_option' => array($container_entity->group_admin_acl => "Group Admin: ".$container_entity->name))); 
	}
	else{
		echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
	}
	?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => $submit_label));

?>
</div>
