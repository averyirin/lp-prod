<?php
?>
<div class='template-header col-lg-12'>
    <h2><?php echo elgg_echo('projects:dashboard:title');?></h2>
    <div class="btn-group">      
      
<?php

echo elgg_view('output/url', array(
            'name' => 'export',
            'text' => elgg_echo('export elgg view'),
            'href' => elgg_get_site_url().'action/support_request_export/export',
            'class' => 'elgg-button elgg-button-action',
            'is_action' => true,
		'is_trusted' => true
    ));

 ?>
         <!--   <a href='#/projects/create' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:create');?></a> -->
            <a href='#/projects' class='elgg-button elgg-button-action'><?php echo elgg_echo('projects:all:list');?></a>
        </div>
</div>

 <nav class="col-lg-2">
<div  id="sidebar" style="margin-bottom:10px;">
        <h3 style="" ><?php echo elgg_echo('support_request:filterByCollection');?></h3>
        <ul>

            <li>
                <a class="list-group-item  list-collection active"  href="" ng-click='vm.filter($event)' id="false" data-filter-type="archived"><?php echo elgg_echo('support_request:current'); ?></a>
            </li>

            <li>
                <a class="list-group-item list-collection" href="" ng-click='vm.filter($event)' id="true" data-filter-type="archived"><?php echo elgg_echo('support_request:archived'); ?></a>
            </li>

        </ul>
    </div>


<div  id="sidebar" style="margin-bottom:10px;">
       <h3><?php echo elgg_echo('projects:filterByStatus'); ?></h3>
        <ul>
            <li>
                <a class="list-group-item list-status active" href="" ng-click='vm.filter($event)' id="" data-filter-type="status"><?php echo elgg_echo('projects:label:all'); ?></a>
            </li>
            <li>
                <a class="list-group-item list-status" href="" ng-click='vm.filter($event)' id="<?php echo elgg_echo('projects:label:submitted', "en"); ?>" data-filter-type="status"><?php echo elgg_echo('projects:label:submitted'); ?></a>
            </li>
            <li>
                <a class="list-group-item list-status" href="" ng-click='vm.filter($event)' id="<?php echo elgg_echo('projects:label:underreview', "en"); ?>" data-filter-type="status"><?php echo elgg_echo('projects:label:underreview'); ?></a>
            </li>
            <li>
                <a class="list-group-item list-status" href="" ng-click='vm.filter($event)' id="<?php echo elgg_echo('projects:label:inprogress', "en"); ?>" data-filter-type="status"><?php echo elgg_echo('projects:label:inprogress'); ?></a>
            </li>
            <li>
                <a class="list-group-item list-status" href="" ng-click='vm.filter($event)' id="<?php echo elgg_echo('projects:label:completed', "en"); ?>" data-filter-type="status"><?php echo elgg_echo('projects:label:completed'); ?></a>
            </li>
	 <li>
                <a class="list-group-item list-status" href="" ng-click='vm.filter($event)' id="<?php echo elgg_echo('projects:label:cancelled', "en"); ?>" data-filter-type="status"><?php echo elgg_echo('projects:label:cancelled'); ?></a>
            </li>
        </ul>
    </div>


</nav>



<section class="col-lg-10">
<!--
    <div style="margin-bottom:1em;" id="sidebar">
        <h3 style="" ><?php echo elgg_echo('support_request:filterByCollection');?></h3>
        <ul>

            <li>
                <a class="list-group-item active"  href="" ng-click='vm.filter($event)' id="false" data-filter-type="archived"><?php echo elgg_echo('support_request:current'); ?></a>
            </li>

            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="true" data-filter-type="archived"><?php echo elgg_echo('support_request:archived'); ?></a>
            </li>

        </ul>
    </div>
-->
    <div class='wb-tabs'>


        <ul role="tablist" class="generated">

            <li>
<a href="" ng-click="vm.filterProjects('all'); vm.toggleFilterTab();" id="all" class="ng-binding">All</a>
            </li>


            <li>
<a href="" ng-click="vm.filterProjects('modernization'); vm.toggleFilterTab();" id="modernization" class="ng-binding">IT&amp;E Modernization</a>
            </li>



            <li>
<a href="" ng-click="vm.filterProjects('lsc'); vm.toggleFilterTab();" id="lsc" class="ng-binding">Learning Support Centre</a>            
		</li>

            <li>
<a href="" ng-click="vm.filterProjects('alsc'); vm.toggleFilterTab();" id="alsc" class="ng-binding">RCAF Learning Support Centre</a>
            </li>

            <li>
<a href="" ng-click="vm.filterProjects('undefined'); vm.toggleFilterTab();" id="undefined" class="ng-binding">Unassigned</a>
            </li>



        </ul>

        
        <div class="tabpanels">
            <div>
                <table class='data-table' datatable="ng" dt-options="vm.dtOptions">
                    <thead>
                        <tr>
                          <!--  <th><?php echo elgg_echo('projects:departmentOwner'); ?></th> -->
                            <th><?php echo elgg_echo('projects:title'); ?></th>
                            <th><?php echo elgg_echo('projects:status'); ?></th>
                         <!--   <th><?php echo elgg_echo('projects:percentage'); ?></th> -->
                            <th><?php echo elgg_echo('projects:submittedBy'); ?></th>
                            <th><?php echo elgg_echo('projects:dateSubmitted'); ?></th>
                          <!--  <th><?php echo elgg_echo('projects:archive'); ?></th>-->


                            <th>


<div  ng-if="vm.filters.archived == 'false'"><?php echo elgg_echo('Archive'); ?></div>
<div  ng-if="vm.filters.archived == 'true'"><?php echo elgg_echo('Unarchive'); ?></div>

</th>



                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat='(key,project) in vm.projects'>
                        <!--    <td>{{project.department_owner}}</td> -->
                            <td>

<a ng-click="vm.selectProject(project)" href="">{{project.title}}</a>

<br/>
<span style="font-size:85%" ng-bind-html="project.cleanDescription"></span>




				</td>
                            <td>{{project.status}}</td>
                           <!-- <td>{{project.percentage}}</td> -->
                            <td>{{project.owner}}</td>
                            <td>{{project.time_created}}</td>
                            <td style="text-align:center">
				

				<a ng-if="vm.filters.archived == 'true'"  ng-if="user.project_admin"  ng-click='vm.unarchiveProject(project.id)' ng-delete-once="<?php echo elgg_echo('support_request:askUnarchiveProject');?>"
 class='elgg-icon elgg-icon-lock-closed' title="<?php echo elgg_echo('support_request:unarchiveProject');?>"></a>
                                <a ng-if="vm.filters.archived == 'false'"   ng-if="user.project_admin" ng-click='vm.archiveProject(project.id)' ng-delete-once="<?php echo elgg_echo('support_request:askArchiveProject');?>" 
class='elgg-icon elgg-icon-lock-open' title="<?php echo elgg_echo('support_request:archiveProject');?>"></a>


				</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</section>

<div class="full-screen" ng-if="vm.project" ng-click="vm.project = false;">
    
    <section class="modal-screen typography">
        
        <h2>{{vm.project.title}}</h2>
        
        <div class="row">
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:description');?></label>
                <p ng-bind-html="vm.project.description"></p>
            </div>
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:investment');?></label>
                <p>{{vm.project.investment}}</p>
            </div>
            
            <div class="col-sm-4">
                <label><?php echo elgg_echo('projects:risk');?></label>
                <p>{{vm.project.risk}}</p>
            </div>
            
        </div>
        
    </section>
    
</div>