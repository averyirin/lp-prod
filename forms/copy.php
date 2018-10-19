<?php
$group = $vars['entity'];

?>
<div>
<label><?php echo elgg_echo("groups:name"); ?></label>
<?php echo elgg_view("input/text", array(
	'name' => 'name',
	'value' => 'Copy of '.$group->name,
)); ?>
</div>

<div>
	<label class="inline" for ="inheritMembers"><?php echo elgg_echo("groups:inherit_members"); ?></label>
	<input type="checkbox" class="inline checkbox" name="inheritMembers" />
</div>

<div>
	<label class="inline" for ="inheritFiles"><?php echo elgg_echo("groups:inherit_files"); ?></label>
	<input type="checkbox" class="inline checkbox" name="inheritFiles" checked />
</div>

<div>
	<label class="inline" for ="inheritForums"><?php echo elgg_echo("groups:inherit_forums"); ?></label>
	<input type="checkbox" class="inline checkbox" name="inheritForums" checked />
</div>
<div>
	<label class="inline" for ="inheritPages"><?php echo elgg_echo("groups:inherit_pages"); ?></label>
	<input type="checkbox" class="inline checkbox" name="inheritPages" checked />
</div>

<div>
	<label class="inline" for ="subGroups"><?php echo elgg_echo("groups:copy_subGroups"); ?></label>
	<input type="checkbox" class="inline checkbox" name="subGroups" checked />
</div>


<?php
echo elgg_view('input/hidden', array(
	'name' => 'group_guid',
	'value' => $group->guid,
));

echo elgg_view('input/hidden', array(
	'name' => 'access_id',
	'value' => $group->access_id,
));

echo elgg_view('input/hidden', array(
	'name' => 'membership',
	'value' => $group->guid,
));
echo elgg_view('input/submit', array('value' => elgg_echo('Copy')));
?>