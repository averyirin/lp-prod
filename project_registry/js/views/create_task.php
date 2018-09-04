<?php ?>
<div class='template-header'>
    <h2><?php echo elgg_echo('support_request:task:create_title'); ?></h2>
    <a href='#/projects/request_support' class='elgg-button elgg-button-action'><?php echo elgg_echo('support_request:back'); ?></a>
</div>
<div class="row">
    <div class="col-sm-4">
        <div ng-include="'projects/toc_task'">
        </div>
    </div>
    <div class='project-form project col-sm-7'>
        <form name='projectForm' ng-submit="vm.createTask(projectForm.$valid)" ng-focus-error="" novalidate>
            <div class='row form-row' data-row-id="title">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:title'); ?></label>
                </div>

                <div class='col-sm-12 field-body'>
                    <input type='text' class='' name='title' ng-model='vm.project.title' required/>
                    <div ng-messages="projectForm.title.$error" ng-if="(projectForm.title.$touched) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row' data-row-id="course">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:course'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <input type='text' class='' name='course' ng-model='vm.project.course'/>
                </div>
            </div>
            <div class='row form-row' data-row-id="org">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:org'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <input type='text' class='' name="org" ng-model='vm.project.org' required/>
                    <div ng-messages="projectForm.org.$error" ng-if="(projectForm.org.$touched) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row' data-row-id="ta">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:ta'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <select ng-model='vm.project.ta' ng-options='option for option in vm.ta_options.values' ng-change='vm.setDepartmentOwner(vm.project.ta)' required></select>

                </div>
            </div>
            <div class='row form-row' data-row-id="type">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:department_owner');?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <input type='text' class='' name='department_owner' ng-model='vm.project.department_owner' disabled="disabled"/>
                </div>
            </div>
            <div class='row form-row' data-row-id="type">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:type'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <select ng-model=vm.project.project_type ng-options='type for type in vm.projectTypes.values'>
                    </select>
                </div>
            </div>
            <div class='row form-row' data-row-id="description">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:description'); ?></label>
                    <p><?php echo elgg_echo('projects:description:helptext'); ?></p>
                </div>
                <div class='col-sm-12 field-body'>
                    <textarea mce-init="description" id="description" name='description' ng-model='vm.project.description'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="timeline">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('support_request:task:completion_date'); ?></label>

                </div>
                <div class='col-sm-12 field-body'>
                    <input type="date" ng-model='vm.project.timeline'/>
                </div>
            </div>

            <div class='row form-row' data-row-id="attachments">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:files'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <input type="file" ngf-select="" ng-model="vm.files" name="file" ngf-multiple="true">
                </div>
            </div>


            <button type='submit' class='elgg-button elgg-button-action'><?php echo elgg_echo('support_request:task:submit'); ?></button>
        </form>
    </div>
</div>
