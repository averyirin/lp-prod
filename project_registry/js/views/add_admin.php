<?php ?>
<div class='template-header'>
    <h2><?php echo elgg_echo('projects:add'); ?></h2>
    <a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list'); ?></a>
</div>
<div class="row">
    <div class="col-sm-8">
        <h3>Begin typing the users name</h3>
        <input type="text" ng-model="selected_user" uib-typeahead="user as user.name for user in vm.users | filter:$viewValue | limitTo:8" class="form-control" id="name">
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <select ng-model=vm.types ng-options='type for type in vm.department_options.values' multiple></select>
    </div>
</div>
<div class="row" ng-show="selected_user">
    <div class="col-sm-12">
        <h4>{{selected_user.name}}</h4>
    </div>

    <div class="col-sm-12">
        <a class='elgg-button elgg-button-action elgg-button-accept' ng-click="vm.addProjectAdmin(selected_user.id)">Add Admin</a>
    </div>
</div>