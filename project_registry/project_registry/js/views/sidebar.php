<?php
 
?>
<nav id="sidebar">
    <div>
        <h3><?php echo elgg_echo('projects:filterByStatus'); ?></h3>
        <ul>
            <li>
                <a class="list-group-item active" href="" ng-click='vm.filter($event)' id="All" data-filter-type="status"><?php echo elgg_echo('projects:label:all'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Submitted" data-filter-type="status"><?php echo elgg_echo('projects:label:submitted'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Under Review" data-filter-type="status"><?php echo elgg_echo('projects:label:underreview'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="In Progress" data-filter-type="status"><?php echo elgg_echo('projects:label:inprogress'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Completed" data-filter-type="status"><?php echo elgg_echo('projects:label:completed'); ?></a>
            </li>
        </ul>
    </div>
    
    <div>
        <h3><?php echo elgg_echo('projects:filterByType'); ?></h3>
        <ul>
            <li>
                <a class="list-group-item active" href="" ng-click='vm.filter($event)' id="All" data-filter-type="project_type"><?php echo elgg_echo('projects:label:all'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Courseware" data-filter-type="project_type"><?php echo elgg_echo('projects:types:courseware'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Enterprise Learning Applications" data-filter-type="project_type"><?php echo elgg_echo('projects:types:enterprise_apps'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Instructor Support" data-filter-type="project_type"><?php echo elgg_echo('projects:types:instructor_support'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Learning Application" data-filter-type="project_type"><?php echo elgg_echo('projects:types:learning_application'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Learning Technologies" data-filter-type="project_type"><?php echo elgg_echo('projects:types:learning_technologies'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Mobile" data-filter-type="project_type"><?php echo elgg_echo('projects:types:mobile'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Modelling and Simulation" data-filter-type="project_type"><?php echo elgg_echo('projects:types:modelling'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="R and D" data-filter-type="project_type"><?php echo elgg_echo('projects:types:rnd'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Serious Gaming" data-filter-type="project_type"><?php echo elgg_echo('projects:types:gaming'); ?></a>
            </li>
            <li>
                <a class="list-group-item" href="" ng-click='vm.filter($event)' id="Support" data-filter-type="project_type"><?php echo elgg_echo('projects:types:support'); ?></a>
            </li>
        </ul>
    </div>
    
</nav>