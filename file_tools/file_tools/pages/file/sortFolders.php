<?php
$groupGuid = get_input("groupGuid");
$sort = get_input("sort");
$sortDir = get_input("sortDir");

$group = get_entity($groupGuid);
$group->folderSort = $sort." ".$sortDir;
if($group->save()){
	echo json_encode(array(
		'success' => true,
		'message' => "Settings have been saved. Reloading page..."
	));
}
else{
	echo json_encode(array(
		'success' => false,
		'message' => "Settings could not be saved"
	));
}