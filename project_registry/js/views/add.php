<?php ?>
<div class='template-header'>
    <h2><?php echo elgg_echo('projects:add'); ?></h2>
    <a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list'); ?></a>
</div>
<div class="row">
    <div class="col-sm-4">
        <div ng-include="'projects/toc'">
        </div>
    </div>
    <div class='project-form project col-sm-7'>
        <form name='projectForm' ng-submit="vm.createProject(projectForm.$valid)" ng-focus-error="" novalidate>
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
                    <div ng-messages="projectForm.scope.$error" ng-if="(projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row' data-row-id="type">
                <div class='col-sm-12 field-header'>
                    <label>Department Owner</label>
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
            <div class='row form-row' data-row-id="scope">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:scope'); ?></label>
                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:scope:helptext:header'); ?></p>
                        <ul>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem1'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem2'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem3'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem4'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem5'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem6'); ?></li>
                            <li><?php echo elgg_echo('projects:scope:helptext:listItem7'); ?></li>
                        </ul>
                    </div>
                </div>
                <div class='col-sm-12 field-body'>
                    <textarea name="scope" ng-model='vm.project.scope' required></textarea>
                    <div ng-messages="projectForm.scope.$error" ng-if="(projectForm.scope.$touched) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row' data-row-id="opi">
                <div class='col-sm-12 field-header'>
                    <label><?php echo elgg_echo('projects:opi'); ?></label>
                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:opi:helptext'); ?></p>
                    </div>
                </div>
                <div class='col-lg-12'>
                    <div class='col-lg-12 field-body'>
                        <div class='row form-row' ng-repeat='(key, opi) in vm.opis'>

                            <div class='col-sm-12 field-header'>
                                <h5><?php echo elgg_echo('projects:opi:title'); ?> {{key + 1}}</h5>
                                <a class='glyphicon remove-button' ng-click='vm.removeContact(key)'></a>
                            </div>

                            <div class='col-sm-12 field-body'>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' name='opi_rank' ng-model='opi.rank' required/>
                                        <div ng-messages="projectForm.opi_rank.$error" ng-if="(projectForm.opi_rank.$touched) || (projectForm.$submitted)">
                                            <div ng-messages-include="projects/messages"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:name'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' name='opi_name' ng-model='opi.name' required/>
                                        <div ng-messages="projectForm.opi_name.$error" ng-if="(projectForm.opi_name.$touched) || (projectForm.$submitted)">
                                            <div ng-messages-include="projects/messages"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:phone'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' name='opi_phone' ng-model='opi.phone' required/>
                                        <div ng-messages="projectForm.opi_phone.$error" ng-if="(projectForm.opi_phone.$touched) || (projectForm.$submitted)">
                                            <div ng-messages-include="projects/messages"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:email'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='email' class='' name='opi_email' ng-model='opi.email' required/>
                                        <div ng-messages="projectForm.opi_email.$error" ng-if="(projectForm.opi_email.$touched) || (projectForm.$submitted)">
                                            <div ng-messages-include="projects/messages"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-12 row'>
                            <a class='elgg-button elgg-button-action' ng-click='vm.addContact()'><?php echo elgg_echo('projects:addContact'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class='row form-row' data-row-id="priority">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:briefExplain'); ?></label>
                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:briefExplain:helptext:header'); ?></p>
                        <ul>
                            <li><?php echo elgg_echo('projects:briefExplain:helptext:listItem1'); ?></li>
                            <li><?php echo elgg_echo('projects:briefExplain:helptext:listItem2'); ?></li>
                            <li><?php echo elgg_echo('projects:briefExplain:helptext:listItem3'); ?>
                                <ol>
                                    <li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem1'); ?></li>
                                    <li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem2'); ?></li>
                                    <li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem3'); ?></li>
                                    <li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem4'); ?></li>
                                    <li><?php echo elgg_echo('projects:briefExplain:helptext:subListItem5'); ?></li>
                                </ol>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class='col-lg-12 field-body'>
                    <textarea ng-model='vm.project.priority'></textarea>
                </div>
            </div>
            <div class='row form-row' data-row-id="sme">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:isSme'); ?></label>
                </div>
                <div class='col-lg-12 field-body'>
                    <select ng-model='vm.project.is_sme_avail' ng-options='option for option in vm.booleanOptions.values'></select>

                    <div class='col-lg-12' id='sme' ng-show=vm.boolOption(vm.project.is_sme_avail)>
                        <div class='row form-row'>
                            <div class='col-lg-12 field-header'>
                                <label><?php echo elgg_echo('projects:sme'); ?></label>
                            </div>

                            <div class='col-sm-12 field-body'>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' ng-model='vm.project.sme.rank'/>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:name'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' ng-model='vm.project.sme.name'/>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:phone'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='text' class='' ng-model='vm.project.sme.phone'/>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <label><?php echo elgg_echo('projects:email'); ?>:</label>
                                    </div>
                                    <div class='col-sm-9'>
                                        <input type='email' name='sme_email' class='' ng-model='vm.project.sme.email'/>
                                        <div ng-messages="projectForm.sme_email.$error" ng-if="(projectForm.sme_email.$dirty) || (projectForm.$submitted)">
                                            <div ng-messages-include="projects/messages"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row form-row' data-row-id="is_limitation">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:isLimitation'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <select ng-model='vm.project.is_limitation' ng-options='option for option in vm.booleanOptions.values'></select>
                </div>
            </div>

            <div class='row form-row' data-row-id="update_existing_product">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:updateExistingProduct'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <select ng-model='vm.project.update_existing_product' ng-options='option for option in vm.multiOptions.values'></select>
                </div>
            </div>

            <div class='row form-row' data-row-id="life_expectancy">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:lifeExpectancy'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <input type='text' name='lifeExpectancy' ng-model='vm.project.life_expectancy'/>
                </div>
            </div>

            <div class='row form-row' data-row-id="usa">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:usa'); ?></label>
                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:email:notification') ?></p>
                        <p><?php echo elgg_echo('projects:usa:helptext'); ?></p>
                    </div>
                </div>
                <div class='col-sm-12 field-body'>
                    <div class='col-sm-3'>
                        <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                    </div>
                    <div class='col-sm-9'>
                        <input type='text' class='' name='usa_rank' ng-model='vm.project.usa.rank' required/>
                        <div ng-messages="projectForm.usa_rank.$error" ng-if="projectForm.usa_rank.$touched || projectForm.$submitted">
                            <div ng-messages-include='projects/messages'></div>
                        </div>
                    </div>
                    <div class='col-sm-3'>
                        <label><?php echo elgg_echo('projects:name'); ?>:</label>
                    </div>
                    <div class='col-sm-9'>
                        <input type='text' class='' name='usa_name' ng-model='vm.project.usa.name' required/>
                        <div ng-messages="projectForm.usa_name.$error" ng-if="(projectForm.usa_name.$touched) || (projectForm.$submitted)">
                            <div ng-messages-include="projects/messages"></div>
                        </div>
                    </div>
                    <div class='col-sm-3'>
                        <label><?php echo elgg_echo('projects:position'); ?>:</label>
                    </div>
                    <div class='col-sm-9'>
                        <input type='text' class='' name='usa_position' ng-model='vm.project.usa.position' required/>
                        <div ng-messages="projectForm.usa_position.$error" ng-if="(projectForm.usa_position.$touched) || (projectForm.$submitted)">
                            <div ng-messages-include="projects/messages"></div>
                        </div>
                    </div>
                    <div class='col-sm-3'>
                        <label><?php echo elgg_echo('projects:email'); ?>:</label>
                    </div>
                    <div class='col-sm-9'>
                        <input type='email' class='' name='usa_email' ng-model='vm.project.usa.email' required/>
                        <div ng-messages="projectForm.usa_email.$error" ng-if="(projectForm.usa_email.$touched) || (projectForm.$submitted)">
                            <div ng-messages-include="projects/messages"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row form-row' data-row-id="comments">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:comments'); ?></label>
                </div>
                <div class='col-sm-12 field-body'>
                    <textarea ng-model='vm.project.comments'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="investment">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:investment'); ?></label>

                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:investment:helptext'); ?></p>
                    </div>

                </div>
                <div class='col-sm-12 field-body'>
                    <textarea ng-model='vm.project.investment'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="risk">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:risk'); ?></label>

                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:risk:helptext'); ?></p>
                    </div>

                </div>
                <div class='col-sm-12 field-body'>
                    <textarea ng-model='vm.project.risk'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="timeline">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:timeline'); ?></label>

                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:timeline:helptext'); ?></p>
                    </div>

                </div>
                <div class='col-sm-12 field-body'>
                    <textarea ng-model='vm.project.timeline'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="impact">
                <div class='col-lg-12 field-header'>
                    <label><?php echo elgg_echo('projects:impact'); ?></label>

                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:impact:helptext:header'); ?></p>
                        <ul>
                            <li><?php echo elgg_echo('projects:impact:helptext:listItem1'); ?></li>
                            <li><?php echo elgg_echo('projects:impact:helptext:listItem2'); ?></li>
                            <li><?php echo elgg_echo('projects:impact:helptext:listItem3'); ?></li>
                        </ul>
                    </div>

                </div>
                <div class='col-sm-12 field-body'>
                    <textarea ng-model='vm.project.impact'></textarea>
                </div>
            </div>

            <div class='row form-row' data-row-id="savings">
                <div class='col-lg-12 field-header'>

                    <label><?php echo elgg_echo('projects:savings'); ?></label>

                    <div class="help-text">
                        <p><?php echo elgg_echo('projects:savings:helpText'); ?></p>
                    </div>

                </div>

                <div class='col-sm-12 field-body'>

                    <div class='row'>
                        <div class='col-lg-12'>
                            <label><?php echo elgg_echo('projects:savings:label'); ?>:</label>
                        </div>
                        <div class='col-lg-12'>
                            <label ng-repeat="(key, choice) in vm.choices"><input type='checkbox' ng-model='choice.selected' value={{choice.title}}>{{choice.title}}</label>
                        </div>
                    </div>

                    <label><?php echo elgg_echo('projects:savings:substantiation'); ?>:</label>
                    <textarea ng-model='vm.project.savings.substantiation'></textarea>

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


            <button type='submit' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:submit'); ?></button>
        </form>
    </div>
</div>
