<?php
?>
<div class='template-header col-lg-12'>
    <h2><?php echo elgg_echo('projects:dashboard:title');?></h2>
    <div class="btn-group">
            <a href='#/projects/create' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:create');?></a>
            <a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list');?></a>
        </div>
</div>

<section class="col-lg-12">
    <div class='wb-tabs'>
        
        <ul role="tablist" class="generated">
            <li ng-repeat="filter in vm.filterTabs | orderBy:'title'" ng-class="{active:$first}">
                <a href='' ng-click="vm.filterProjects(filter.id); vm.toggleFilterTab($event);" id='all'>{{filter.title}}</a>
            </li>
        </ul>
        
        <div class="tabpanels">
            <div>
                <table class='data-table' datatable="ng" dt-options="vm.dtOptions">
                    <thead>
                        <tr>
                            <th><?php echo elgg_echo('projects:departmentOwner'); ?></th>
                            <th><?php echo elgg_echo('projects:title'); ?></th>
                            <th><?php echo elgg_echo('projects:status'); ?></th>
                            <th><?php echo elgg_echo('projects:percentage'); ?></th>
                            <th><?php echo elgg_echo('projects:submittedBy'); ?></th>
                            <th><?php echo elgg_echo('projects:dateSubmitted'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat='(key,project) in vm.projects'>
                            <td>{{project.department_owner}}</td>
                            <td><a ng-click="vm.selectProject(project)" href="">{{project.title}}</a></td>
                            <td>{{project.status}}</td>
                            <td>{{project.percentage}}</td>
                            <td>{{project.owner}}</td>
                            <td>{{project.time_created}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</section>

<div class="full-screen" ng-if="vm.project">
    
    <section class="modal-screen typography">
        
        <h2>{{vm.project.title}}</h2>
        
        <div class="row">
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:description');?></label>
                <p>{{vm.project.description}}</p>
            </div>
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:description');?></label>
                <p>{{vm.project.investment}}</p>
            </div>
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:description');?></label>
                <p>{{vm.project.risk}}</p>
            </div>
            
        </div>
        
    </section>
    
</div>