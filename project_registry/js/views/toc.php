<?php
 
?>
<nav id="sidebar">
	<div class="table-of-contents">
		<h3><?php echo elgg_echo('projects:toc'); ?></h3>
		<ul>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="title"><?php echo elgg_echo('projects:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="course"><?php echo elgg_echo('projects:course'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="org"><?php echo elgg_echo('projects:org'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="ta"><?php echo elgg_echo('projects:ta'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="type"><?php echo elgg_echo('projects:type'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="description"><?php echo elgg_echo('projects:description'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="scope"><?php echo elgg_echo('projects:scope'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="opi"><?php echo elgg_echo('projects:opi:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="priority"><?php echo elgg_echo('projects:priority:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="sme"><?php echo elgg_echo('projects:sme'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="is_limitation"><?php echo elgg_echo('projects:isLimitation:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="update_existing_product"><?php echo elgg_echo('projects:updateExistingProduct:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="life_expectancy"><?php echo elgg_echo('projects:lifeExpectancy:title'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="comments"><?php echo elgg_echo('projects:comments'); ?></a>
			</li>
			<li>
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="attachments"><?php echo elgg_echo('projects:files'); ?></a>
			</li>
		</ul>
	</div>
</nav>
<script>
	$(function(){
		var sidebar = $('#sidebar');
		var toc = $('.table-of-contents');
		var sidebarTop = sidebar.offset().top + 4;
		var windowWidth = window.innerWidth;
		
		$(window).scroll(function() {
				sidebarTop = $('#sidebar').offset().top + 4;
				var windowTop = $(window).scrollTop();

				if(sidebarTop < windowTop && window.innerWidth > 768) {
					toc.css('position', 'fixed');
					toc.css('top', 0);
					toc.css('width', $('#sidebar').width());
				}
				else if(sidebarTop > windowTop || window.innerWidth < 768) {
					toc.css('position', 'static');
				}
		});
	});
</script>