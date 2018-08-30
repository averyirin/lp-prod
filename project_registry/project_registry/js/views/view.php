<?php
?>
<div ng-show='vm.loaded'>
	<div class='template-header'>
		<h2>{{vm.project.title}}</h2>
		<a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list'); ?></a>
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
			<div ng-include="'projects/toc'">
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
					<p data-field-id="description">{{vm.project.description}}</p>

					<div ng-if="vm.project.editable['description']">
						<textarea name='description' ng-model='vm.description' ng-minlength='3' ng-maxlength='500' required></textarea>
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

			<div class='form-row clearfix' data-row-id="scope">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:scope'); ?></label>
					<a class='glyphicon edit-button scope' data-id ="scope" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<div class="help-text">
						<p><?php echo elgg_echo('projects:scope:helptext:header');?></p>
						<ul>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem1');?></li>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem2');?></li>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem3');?></li>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem4');?></li>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem5');?></li>
							<li><?php echo elgg_echo('projects:scope:helptext:listItem6');?></li>
						</ul>
					</div>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="scope">{{vm.project.scope}}</p>

					<div ng-if="vm.project.editable['scope']">
						<textarea name='scope' ng-model='vm.scope' required></textarea>
						<div ng-messages="projectForm.scope.$error" ng-if="(projectForm.scope.$dirty) || (projectForm.$submitted)">
							<div ng-messages-include="projects/messages"></div>
						</div>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="scope" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="scope" ng-click="vm.update('scope'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="opi">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:opi');?></label>
					<div class="help-text">
						<p><?php echo elgg_echo('projects:opi:helptext'); ?></p>
					</div>
				</div>
				<div class='col-lg-12'>
					<div class='col-sm-12 field-body'>
						<div class='form-row clearfix' ng-repeat='(key, opi) in vm.opis'>

							<div class='col-sm-12 field-header'>
								<h5><?php echo elgg_echo('projects:opi:title'); ?> {{$index+1}}</h5>
								<a class='glyphicon edit-button opi' data-id ="opi" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event, $index)"></a>
								<a class='glyphicon remove-button' ng-click='vm.removeContact(key)'></a>
							</div>

							<div class='col-sm-12 field-body' data-field-id="opi">
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:rank');?>:</label>
									</div>
									<div class='col-sm-9'>
										<p>{{opi.rank}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:name');?>:</label>
									</div>
									<div class='col-sm-9'>
										<p>{{opi.name}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:phone');?>:</label>
									</div>
									<div class='col-sm-9'>
										<p>{{opi.phone}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:email'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<p>{{opi.email}}</p>
									</div>
								</div>
							</div>

							<div class="col-lg-12 field-body" ng-if="vm.project.editable['opi'][$index]">
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:rank'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' class='' name='opi_rank' ng-model='opi.rank' required />
										<div ng-messages="projectForm.opi_rank.$error" ng-if="(projectForm.opi_rank.$dirty) || (projectForm.$submitted)">
											<div ng-messages-include="projects/messages"></div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:name'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' name='opi_name' class='' ng-model='opi.name' required />
										<div ng-messages="projectForm.opi_name.$error" ng-if="(projectForm.opi_name.$dirty) || (projectForm.$submitted)">
											<div ng-messages-include="projects/messages"></div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:phone'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' name='opi_phone' class='' ng-model='opi.phone' required />
										<div ng-messages="projectForm.opi_phone.$error" ng-if="(projectForm.opi_phone.$dirty) || (projectForm.$submitted)">
											<div ng-messages-include="projects/messages"></div>
										</div>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:email'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='email' name='opi_email' class='' ng-model='opi.email' required />
										<div ng-messages="projectForm.opi_email.$error" ng-if="(projectForm.opi_email.$dirty) || (projectForm.$submitted)">
											<div ng-messages-include="projects/messages"></div>
										</div>
									</div>
								</div>
								<div class='editable-content-buttons'>
									<a class='elgg-button elgg-button-action elgg-button-cancel' data-id ="opi" ng-click="vm.toggleEditMode($event, $index)"><?php echo elgg_echo('projects:cancel'); ?></a>
									<a class='elgg-button elgg-button-action elgg-button-accept' data-id ="opi" ng-click="vm.update('opis'); vm.toggleEditMode($event, $index)"><?php echo elgg_echo('projects:accept'); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-row clearfix" data-row-id="priority">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:briefExplain'); ?></label>
					<a class='glyphicon edit-button priority' data-id ="priority" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<div class="help-text">
						<p><?php echo elgg_echo('projects:briefExplain:helptext:header');?></p>
						<ul>
							<li><?php echo elgg_echo('projects:briefExplain:helptext:listItem1');?></li>
							<li><?php echo elgg_echo('projects:briefExplain:helptext:listItem2');?></li>
							<li><?php echo elgg_echo('projects:briefExplain:helptext:listItem3');?>
								<ol>
									<li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem1');?></li>
									<li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem2');?></li>
									<li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem3');?></li>
									<li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem4');?></li>
									<li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem5');?></li>
								</ol>
							</li>
						</ul>
					</div>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="priority">{{vm.project.priority}}</p>

					<div ng-if="vm.project.editable['priority']">
						<textarea ng-model='vm.priority' value='{{vm.project.priority}}'></textarea>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="priority" ng-click="vm.toggleEditMode($event)">Cancel</a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="priority" ng-click="vm.update('priority'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="sme">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:isSme'); ?></label>
					<a class='glyphicon edit-button is_sme_avail' data-id ="is_sme_avail" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="is_sme_avail">{{vm.project.is_sme_avail}}</p>

					<div ng-if="vm.project.editable['is_sme_avail']">
						<select ng-model='vm.is_sme_avail' ng-options='option for option in vm.booleanOptions.values'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="is_sme_avail" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="is_sme_avail" ng-click="vm.update('is_sme_avail'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>

					<div class='col-lg-12' id='sme' ng-show=vm.boolOption(vm.project.is_sme_avail)>
						<div class='form-row clearfix'>
							<div class='col-sm-12 field-header'>
								<label><?php echo elgg_echo('projects:sme'); ?></label>
								<a class='glyphicon edit-button sme' data-id ="sme" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
							</div>

							<div class='col-sm-12 field-body' data-field-id="sme">
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:rank'); ?> :</label>
									</div>
									<div class='col-sm-9'>
										<p>{{vm.project.sme.rank}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:name');?> :</label>
									</div>
									<div class='col-sm-9'>
										<p>{{vm.project.sme.name}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:phone');?> :</label>
									</div>
									<div class='col-sm-9'>
										<p>{{vm.project.sme.phone}}</p>
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:email'); ?> :</label>
									</div>
									<div class='col-sm-9'>
										<p>{{vm.project.sme.email}}</p>
									</div>
								</div>
							</div>

							<div class="col-sm-12 field-body" ng-if="vm.project.editable['sme']">
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:rank'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' class='' ng-model='vm.sme.rank' />
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:name'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' class='' ng-model='vm.sme.name' />
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:phone'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='text' class='' ng-model='vm.sme.phone' />
									</div>
								</div>
								<div class='row'>
									<div class='col-sm-3'>
										<label><?php echo elgg_echo('projects:email'); ?>:</label>
									</div>
									<div class='col-sm-9'>
										<input type='email' name='sme_email' class='' ng-model='vm.sme.email' />
										<div ng-messages="projectForm.sme_email.$error" ng-if="(projectForm.sme_email.$dirty) || (projectForm.$submitted)">
											<div ng-messages-include="projects/messages"></div>
										</div>
									</div>
								</div>
								<div class='editable-content-buttons'>
									<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="sme" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
									<a class='elgg-button elgg-button-action elgg-button-accept' data-id="sme" ng-click="vm.update('sme'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="is_limitation">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:isLimitation'); ?></label>
					<a class='glyphicon edit-button is_limitation' data-id="is_limitation" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="is_limitation">{{vm.project.is_limitation}}</p>

					<div ng-if="vm.project.editable['is_limitation']">
						<select ng-model='vm.is_limitation' ng-options='option for option in vm.booleanOptions.values'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="is_limitation" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="is_limitation" ng-click="vm.update('is_limitation'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="update_existing_product">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:updateExistingProduct'); ?></label>
					<a class='glyphicon edit-button update_existing_product' data-id="update_existing_product" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="update_existing_product">{{vm.project.update_existing_product}}</p>

					<div ng-if="vm.project.editable['update_existing_product']">
						<select ng-model='vm.update_existing_product' ng-options='option for option in vm.multiOptions.values'></select>
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="update_existing_product" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="update_existing_product" ng-click="vm.update('update_existing_product'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="life_expectancy">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:lifeExpectancy');?></label>
					<a class='glyphicon edit-button life_expectancy' data-id="life_expectancy" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="life_expectancy">{{vm.project.life_expectancy}}</p>

					<div ng-if="vm.project.editable['life_expectancy']">
						<input type='text' name='life_expectancy' ng-model='vm.life_expectancy' />
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="life_expectancy" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="life_expectancy" ng-click="vm.update('life_expectancy'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="usa">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:usa'); ?></label>
					<a class='glyphicon edit-button usa' data-id="usa" ng-if='user.project_admin' ng-click="vm.toggleEditMode($event)"></a>
					<div class="help-text">
						<p><?php echo elgg_echo('projects:usa:helptext'); ?></p>
					</div>
				</div>
				
				<div class='col-sm-12 field-body' data-field-id='usa'>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:rank');?> :</label>
						</div>
						<div class='col-sm-9'>
							<p>{{vm.project.usa.rank}}</p>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:name');?> :</label>
						</div>
						<div class='col-sm-9'>
							<p>{{vm.project.usa.name}}</p>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:position'); ?> :</label>
						</div>
						<div class='col-sm-9'>
							<p>{{vm.project.usa.position}}</p>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:email');?> :</label>
						</div>
						<div class='col-sm-9'>
							<p>{{vm.project.usa.email}}</p>
						</div>
					</div>
				</div>
				
				<div class='col-sm-12 field-body' ng-if="vm.project.editable['usa']">
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:rank');?> :</label>
						</div>
						
						<div class='col-sm-9'>
							<input type='text' class='' name='usa_rank' ng-model='vm.usa.rank' required/>
							
							<div ng-messages="projectForm.usa_rank.$error" ng-if="projectForm.usa_rank.$touched || projectForm.$submitted">
								<div ng-messages-include='projects/messages'></div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:name');?> :</label>
						</div>
						
						<div class='col-sm-9'>
							<input type='text' class='' name='usa_name' ng-model='vm.usa.name' required/>
							
							<div ng-messages="projectForm.usa_name.$error" ng-if="(projectForm.usa_name.$touched) || (projectForm.$submitted)">
								<div ng-messages-include="projects/messages"></div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:position'); ?> :</label>
						</div>
						
						<div class='col-sm-9'>
							<input type='text' class='' name='usa_position' ng-model='vm.usa.position' required/>
							
							<div ng-messages="projectForm.usa_position.$error" ng-if="(projectForm.usa_position.$touched) || (projectForm.$submitted)">
								<div ng-messages-include="projects/messages"></div>
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-3'>
							<label><?php echo elgg_echo('projects:email');?> :</label>
						</div>
						<div class='col-sm-9'>
							<input type='email' class='' name='usa_email' ng-model='vm.usa.email' required/>
							<div ng-messages="projectForm.usa_email.$error" ng-if="(projectForm.usa_email.$touched) || (projectForm.$submitted)">
								<div ng-messages-include="projects/messages"></div>
							</div>
						</div>
					</div>
					
					<div class='editable-content-buttons'>
						<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="usa" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
						<a class='elgg-button elgg-button-action elgg-button-accept' data-id="usa" ng-click="vm.update('usa'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
					</div>
				</div>
				
			</div>

			<div class='form-row clearfix' data-row-id="comments">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:comments'); ?></label>
					<a class='glyphicon edit-button comments' data-id="comments" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
				</div>
				<div class='col-sm-12 field-body'>
					<p data-field-id="comments">{{vm.project.comments}}</p>

					<div ng-if="vm.project.editable['comments']">
						<input type='text' name='comments' ng-model='vm.comments' />
						<div class='editable-content-buttons'>
							<a class='elgg-button elgg-button-action elgg-button-cancel' data-id="comments" ng-click="vm.toggleEditMode($event)"><?php echo elgg_echo('projects:cancel'); ?></a>
							<a class='elgg-button elgg-button-action elgg-button-accept' data-id="comments" ng-click="vm.update('comments'); vm.toggleEditMode($event)"><?php echo elgg_echo('projects:accept'); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class='form-row clearfix' data-row-id="attachments">
				<div class='col-sm-12 field-header'>
					<label><?php echo elgg_echo('projects:files'); ?></label>
				</div>
				<div class='col-sm-12 field-body'>
					<div ng-repeat='attachment in vm.project.attachments'>
						<a href='{{attachment.url}}' >{{attachment.title}}</a>
					</div>
				</div>
			</div>
		</div>

		<div class='project project-sidebar col-sm-3' style="float:right;">
			<div class="project-sidebar-row clearfix">
				<div class="col-sm-12 field-header">
					<label><?php echo elgg_echo('projects:classification'); ?></label>
					<?php if(elgg_is_admin_logged_in()) { ?> 
						<a class='glyphicon edit-button classification' data-id="classification" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<?php } ?> 
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
					<?php if(elgg_is_admin_logged_in()) { ?> 
						<a class='glyphicon edit-button department_owner' data-id="department_owner" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<?php } ?> 
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
					<?php if(elgg_is_admin_logged_in()) { ?> 
						<a class='glyphicon edit-button status' data-id="status" ng-if='vm.project.can_edit' ng-click="vm.toggleEditMode($event)"></a>
					<?php } ?> 
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
					<?php if(elgg_is_admin_logged_in()) { ?>
						<a class='glyphicon edit-button percentage fade' data-id="percentage" ng-show="!vm.project.editable['percentage'] && vm.project.status=='In Progress'" ng-click="vm.toggleEditMode_variant($event)"></a>
						<a class='glyphicon success-button percentage fade' data-id="percentage" ng-show="vm.project.editable['percentage']" ng-click="vm.update('percentage'); vm.toggleEditMode_variant($event)"></a>
					<?php } ?> 
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