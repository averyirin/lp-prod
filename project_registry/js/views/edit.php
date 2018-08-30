<?php ?>
<div ng-show='vm.loaded'>
    <div class='template-header'>
        <h2>Edit Project - {{vm.title}}</h2>
        <a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list'); ?></a>
    </div>
    <div class='project-form project'>
        <form name='projectForm' ng-submit="vm.editProject(projectForm.$valid)" ng-focus-error="" novalidate>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:title'); ?></label>
                </div>
                <div class='col-md-6'>
                    <input type='text' class='' name='title' ng-model='vm.title' required />
                    <div ng-messages="projectForm.title.$error" ng-if="(projectForm.title.$dirty) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:course'); ?></label>
                </div>
                <div class='col-md-6'>
                    <input type='text' class='' name='course' ng-model='vm.course' />
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:org'); ?></label>
                </div>
                <div class='col-md-6'>
                    <input type='text' class='' name='org' ng-model='vm.org' required />
                    <div ng-messages="projectForm.org.$error" ng-if="(projectForm.org.$dirty) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:type'); ?></label>
                </div>
                <div class='col-md-6'>
                    <select ng-model=vm.project.project_type ng-options='type for type in vm.projectTypes.values'>
                    </select>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:description'); ?></label>
                </div>
                <div class='col-md-6'>
                    <textarea name='description' ng-model='vm.description' ng-minlength='3' ng-maxlength='500' required></textarea>
                    <div ng-messages="projectForm.description.$error" ng-if="(projectForm.description.$dirty) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:scope'); ?></label>
                </div>
                <div class='col-md-6'>
                    <textarea name='scope' ng-model='vm.scope' required></textarea>
                    <div ng-messages="projectForm.scope.$error" ng-if="(projectForm.scope.$dirty) || (projectForm.$submitted)">
                        <div ng-messages-include="projects/messages"></div>
                    </div>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:opi'); ?></label>
                </div>
                <div class='col-md-6 row sub-row'>
                    <div class='col-lg-12'>
                        <div ng-repeat='(key, opi) in vm.opis'>
                            <div class='col-lg-12 row'>
                                <h5><?php echo elgg_echo('projects:opi:title'); ?> {{key + 1}}</h5>
                                <button class='elgg-button elgg-button-action form-btn' ng-click='vm.removeContact(key)'><?php echo elgg_echo('projects:removeContact'); ?></button>
                            </div>

                            <div class='row'>
                                <div class='col-md-3'>
                                    <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                                </div>
                                <div class='col-md-9'>
                                    <input type='text' class='' name='opi_rank' ng-model='opi.rank' required />
                                    <div ng-messages="projectForm.opi_rank.$error" ng-if="(projectForm.opi_rank.$dirty) || (projectForm.$submitted)">
                                        <div ng-messages-include="projects/messages"></div>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <label><?php echo elgg_echo('projects:name'); ?>:</label>
                                </div>
                                <div class='col-md-9'>
                                    <input type='text' name='opi_name' class='' ng-model='opi.name' required />
                                    <div ng-messages="projectForm.opi_name.$error" ng-if="(projectForm.opi_name.$dirty) || (projectForm.$submitted)">
                                        <div ng-messages-include="projects/messages"></div>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <label><?php echo elgg_echo('projects:phone'); ?>:</label>
                                </div>
                                <div class='col-md-9'>
                                    <input type='text' name='opi_phone' class='' ng-model='opi.phone' required />
                                    <div ng-messages="projectForm.opi_phone.$error" ng-if="(projectForm.opi_phone.$dirty) || (projectForm.$submitted)">
                                        <div ng-messages-include="projects/messages"></div>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <label><?php echo elgg_echo('projects:email'); ?>:</label>
                                </div>
                                <div class='col-md-9'>
                                    <input type='email' name='opi_email' class='' ng-model='opi.email' required />
                                    <div ng-messages="projectForm.opi_email.$error" ng-if="(projectForm.opi_email.$dirty) || (projectForm.$submitted)">
                                        <div ng-messages-include="projects/messages"></div>
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
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:isPriority'); ?></label>
                </div>
                <div class='col-md-6'>
                    <select ng-model='vm.isPriority' ng-options='option for option in vm.booleanOptions.values' ng-change=vm.toggleContainer(vm.isPriority, 'briefExplain')></select>
                </div>
            </div>
            <div class='row form-row' id='briefExplain' ng-hide='vm.isPriority == vm.booleanOptions.values[0]'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:briefExplain'); ?></label>
                </div>
                <div class='col-md-6'>
                    <textarea ng-model='vm.priority' value='{{vm.project.priority}}'></textarea>
                </div>
            </div>
            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:isSme'); ?></label>
                </div>
                <div class='col-md-6'>
                    <select ng-model='vm.isSme' ng-options='option for option in vm.booleanOptions.values' ng-change=vm.toggleContainer(vm.isSme, 'sme')></select>
                </div>
            </div>
            <div class='row form-row' id='sme' ng-hide='vm.isSme == vm.booleanOptions.values[0]'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:sme'); ?></label>
                </div>
                <div class='col-md-6 sub-row'>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <input type='text' class='' ng-model='vm.project.sme.rank' />
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:name'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <input type='text' class='' ng-model='vm.project.sme.name' />
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:phone'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <input type='text' class='' ng-model='vm.project.sme.phone' />
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:email'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <input type='email' name='sme_email' class='' ng-model='vm.project.sme.email' />
                            <div ng-messages="projectForm.sme_email.$error" ng-if="(projectForm.sme_email.$dirty) || (projectForm.$submitted)">
                                <div ng-messages-include="projects/messages"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:isLimitation'); ?></label>
                </div>
                <div class='col-md-6'>
                    <select ng-model='vm.project.is_limitation' ng-options='option for option in vm.booleanOptions.values'></select>
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:updateExistingProduct'); ?></label>
                </div>
                <div class='col-md-6'>
                    <select ng-model='vm.project.update_existing_product' ng-options='option for option in vm.multiOptions.values'></select>
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:lifeExpectancy'); ?></label>
                </div>
                <div class='col-md-6'>
                    <input type='text' name='lifeExpectancy' ng-model='vm.lifeExpectancy' />
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:usa'); ?></label>
                </div>
                <div class='col-md-6 sub-row'>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:rank'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <p>{{vm.project.usa.rank}}</p>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:name'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <p>{{vm.project.usa.name}}</p>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:position'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <p>{{vm.project.usa.position}}</p>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-3'>
                            <label><?php echo elgg_echo('projects:email'); ?>:</label>
                        </div>
                        <div class='col-md-9'>
                            <p>{{vm.project.usa.email}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:comments'); ?></label>
                </div>
                <div class='col-md-6'>
                    <textarea ng-model='vm.comments'></textarea>
                </div>
            </div>

            <div class='row form-row'>
                <div class='col-md-3'>
                    <label><?php echo elgg_echo('projects:files'); ?></label>
                </div>
                <div class='col-md-6'>
                    <div class='elgg-button' ngf-select ng-model='vm.files' ngf-multiple='true'>Select</div>
                    Drop files: <div ngf-drop ng-model='files'>Drop</div>
                </div>
            </div>

            <button type='submit' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:save'); ?></button>
        </form>
    </div>
</div>