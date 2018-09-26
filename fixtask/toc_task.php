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
				<a class="list-group-item" href="" ng-click='vm.animateToField($event)' data-list-id="timeline"><?php echo elgg_echo('support_request:task:completion_date'); ?></a>
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

				
		if($( "#datepicker" ) != null){
    			$( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
		}

		if($("#sidebar") != null && $("#sidebar") != 'undefined' ){
			sidebarTop = $('#sidebar').offset().top + 4;
			var windowTop = $(window).scrollTop();
			if(sidebarTop < windowTop && window.innerWidth > 768) {
					toc.css('position', 'fixed');
					toc.css('top', 0);
					toc.css('width', $('#sidebar').width());
			} else if(sidebarTop > windowTop || window.innerWidth < 768) {
					toc.css('position', 'static');
			}

		}

		});
	});
</script>
