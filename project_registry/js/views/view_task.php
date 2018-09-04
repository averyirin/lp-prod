<?php
?>
<div ng-show='vm.loaded'>
	<div class='template-header'>
		<h2>{{vm.project.title}}</h2>
		<a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list'); ?></a>
        <a ng-click="vm.print()" href='' target='_blank' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:print'); ?></a>
		<div class="project-brief-info">
			<div class='submitted' ng-if="vm.project.status=='Submitted'">
				<span class='glyphicon exclamation'></span>
				<p>{{vm.project.status}}</p>
			</div>
			<div class='under-review' ng-if="vm.project.status=='Under Review'">
				<span class='glyphicon exclamation'></span>
				<p>{{vm.project.status}}</p>
			</div>
			<div class='in-progress' ng-if="vm.project.status=='In Progress'">
				<span class='glyphicon exclamation'></span>
				<p>{{vm.project.status}}</p>
			</div>
			<div class='completed' ng-if="vm.project.status=='Completed'">
				<span class='glyphicon exclamation'></span>
				<p>{{vm.project.status}}</p>
			</div>
			<h4><?php echo elgg_echo('projects:submittedBy');?>: <span>{{vm.project.owner}}</span> <?php echo elgg_echo('projects:on');?> {{vm.project.time_created}}</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<div ng-include="'projects/toc_task'">
			</div>
		</div>
		<div class='project col-sm-6'>
			<div class='form-row clearfix' data-row-id="title">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:title');?></label>
					<a class='glyphicon edit-button title' data-id ="title" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="title">{{vm.project.title}}</p>

					<div ng-if="vm.project.editable['title']">
						<input type='text' class='' name='title' ng-model='vm.title' required />
						<div ng-messages="projectForm.title.$error" ng-if="(projectForm.title.$dirty) || (projectForm.$submitted)">
							<div ng-messages-include="projects/messages"></div>
						</div>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="title" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="title" ng-click="vm.update('title'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class='form-row clearfix' data-row-id="course">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:course');?></label>
					<a class='glyphicon edit-button course' data-id ="course" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="course">{{vm.project.course}}</p>

					<div ng-if="vm.project.editable['course']">
						<input type='text' class='' name='course' ng-model='vm.course' />
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="course" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="course" ng-click="vm.update('course'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class='form-row clearfix' data-row-id="org">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:org');?></label>
					<a class='glyphicon edit-button org' data-id ="org" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="org">{{vm.project.org}}</p>

					<div ng-if="vm.project.editable['org']">
						<input type='text' class='' name='org' ng-model='vm.org' required />
						<div ng-messages="projectForm.org.$error" ng-if="(projectForm.org.$dirty) || (projectForm.$submitted)">
							<div ng-messages-include="projects/messages"></div>
						</div>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="org" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="org" ng-click="vm.update('org'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class='form-row clearfix' data-row-id="ta">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:ta');?></label>
					<a class='glyphicon edit-button ta' data-id ="ta" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="ta">{{vm.ta}}</p>

					<div ng-if="vm.project.editable['ta']">
						{{vm.ta.values}}
						<select ng-model="vm.ta" ng-options='ta for ta in vm.ta_options.values'>
						</select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="ta" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="ta" ng-click="vm.update('ta'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class='form-row clearfix' data-row-id="type">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:type');?></label>
					<a class='glyphicon edit-button project_type' data-id ="project_type" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="project_type">{{vm.project_type}}</p>

					<div ng-if="vm.project.editable['project_type']">
						<select ng-model="vm.project_type" ng-options='type for type in vm.projectTypes.values'>
						</select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="project_type" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="project_type" ng-click="vm.update('project_type'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="description">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:description');?></label>
					<a class='glyphicon edit-button description' data-id ="description" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<p><?php echo elgg_echo('projects:description:helptext');?></p>
				</div>
				<div class='col-sm-12 field-body'>
                    <div class="elgg-output" data-field-id="description" ng-bind-html="vm.project.description"></div>

					<div ng-show="vm.project.editable['description']">
						<textarea mce-init="description" id="description" name='description' ng-model='vm.description'></textarea>
						<div ng-messages="projectForm.description.$error" ng-if="(projectForm.description.$dirty) || (projectForm.$submitted)">
							<div ng-messages-include="projects/messages"></div>
						</div>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="description" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="description" ng-click="vm.update('description'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>



  <div class='form-row clearfix' data-row-id="timeline">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('support_request:task:completion_date'); ?></label>
					<a class='glyphicon edit-button timeline' data-id="timeline" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="timeline">{{vm.project.timeline == null ? "" : vm.project.timeline | date: 'yyyy-MM-dd'}} </p>

					<div ng-if="vm.project.editable['timeline']">
                        <input type="date" ng-model='vm.project.timeline' />
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="timeline" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="timeline" ng-click="vm.update('timeline'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
	</div>

	<div class='form-row clearfix' data-row-id="attachments">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:files'); ?></label>
					<a class='glyphicon edit-button attachments' data-id="attachments" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<div data-field-id="attachments">
						<div ng-repeat='attachment in vm.project.attachments'>
							<a href='{{attachment.url}}' >{{attachment.title}}</a>
						</div>
					</div>

					<div ng-if="vm.project.editable['attachments']">
						<div ng-repeat='(key, attachment) in vm.project.attachments'>
							<div class="row" style="border-bottom: 1px solid #dde4e6;
							padding: 5px;">
								<div class="col-xs-11"  >
										<a href='{{attachment.url}}' >{{attachment.title}}</a>
								</div>
								<div class="col-xs-1">
									<a class="glyphicon delete-button action-item ng-scope"  ng-click="vm.deleteAttachment(vm.project.id, key)" ng-delete-once="Are you sure you want to detach this file {{attachment.title}}? There is no undo!"></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class='col-sm-12 field-header'>
								<label><?php echo elgg_echo('support_request:files:add'); ?></label>
							</div>
							<input type="file" ngf-select="" ng-model="vm.files" name="file" ngf-multiple="true">
				
						</div>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="attachments" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="attachments" ng-click="vm.update('attachments'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
	</div>
	<!--
	<div class='form-row clearfix' data-row-id="attachments">
		<div class='col-sm-12 field-header'>
			<label><?php echo elgg_echo('projects:files'); ?></label>
			<a class='glyphicon edit-button attachments' data-id="attachments" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
		</div>
		<div class='col-sm-12 field-body'>
			<div ng-repeat='attachment in vm.project.attachments'>
				<a href='{{attachment.url}}' >{{attachment.title}}</a>
			</div>
			<div ng-if="vm.project.editable['attachments']">
					<input type="file" ngf-select="" ng-model="vm.files" name="file" ngf-multiple="true">
				<div class='editable-content-buttons'>
					<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="attachments" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
					<a class='elgg-button elgg-button-action elgg-button-accept' data-id="attachments" ng-click="vm.update('attachments'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
				</div>
			</div>
		</div>
	</div>
-->
	<!--
	<div class='row form-row' data-row-id="attachments">
			<div class='col-lg-12 field-header'>
					<label><?php echo elgg_echo('projects:files'); ?></label>
			</div>
			<div class='col-sm-12 field-body'>
					<input type="file" ngf-select="" ng-model="vm.files" name="file" ngf-multiple="true">
			</div>
	</div>
-->

</div>



		<div class='project project-sidebar col-sm-3' style="float:right;">
			<div class="project-sidebar-row clearfix">
				<div class="col-sm-12 field-header">
					<label><?php echo elgg_echo('projects:classification'); ?></label>
					<a class='glyphicon edit-button classification' data-id="classification" ng-if="user.project_admin" ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class="col-sm-12 field-body">
					<p data-field-id="classification">{{vm.project.classification}}</p>

					<div ng-if="vm.project.editable['classification']">
						<select ng-model='vm.classification' ng-options='option for option in vm.classification_options.values'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="classification" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="classification" ng-click="vm.update('classification'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class="project-sidebar-row clearfix">
				<div class="col-sm-12 field-header">
					<label><?php echo elgg_echo('projects:departmentOwner'); ?></label>
					<a class='glyphicon edit-button department_owner' data-id="department_owner" ng-if="user.project_admin" ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class="col-sm-12 field-body">
					<p data-field-id="department_owner">{{vm.project.department_owner}}</p>

					<div ng-if="vm.project.editable['department_owner']">
						<select ng-model='vm.department_owner' ng-options='option for option in vm.department_options.values'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="department_owner" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="department_owner" ng-click="vm.update('department_owner'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class="project-sidebar-row clearfix">
				<div class="col-sm-12 field-header">
					<label><?php echo elgg_echo('projects:status'); ?></label>
					<a class='glyphicon edit-button status' data-id="status" ng-if="user.project_admin" ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class="col-sm-12 field-body">
					<p data-field-id="status">{{vm.project.status}}</p>

					<div ng-if="vm.project.editable['status']">
						<select ng-model='vm.status' ng-options='status.name as status.name for status in vm.statuses'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="status" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="status" ng-click="vm.update('status'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class="project-sidebar-row clearfix">
				<div class="col-sm-12 field-header">
					<label><?php echo elgg_echo('projects:percentage'); ?></label>
						<a class='glyphicon edit-button percentage fade' data-id="percentage" ng-show="!vm.project.editable['percentage'] && vm.project.status=='In Progress' && user.project_admin" ng-click="vm.toggleEditMode_variant($event)"></a>
						<a class='glyphicon success-button percentage fade' data-id="percentage" ng-show="vm.project.editable['percentage']" ng-click="vm.update('percentage'); vm.toggleEditMode_variant($event)"></a>
				</div>
				<div class="col-sm-12 field-body">
					<p data-field-id="percentage">{{vm.percentage}}</p>
					<div class="fade" ng-if="vm.project.editable['percentage']">
						<div ui-slider data-min="0" data-max="100" data-step="5" data-tick ng-model="vm.percentage"></div>
						<p class="helper-text"><?php echo elgg_echo('projects:checkmark:helper');?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
