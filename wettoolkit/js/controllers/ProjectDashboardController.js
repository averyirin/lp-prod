(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('ProjectsDashboard', ProjectsDashboard);

		function ProjectsDashboard(projects, $rootScope) {
            
            var vm = this;
            var projectOwnersLookup = {
                "IT&E Modernization": "modernization",
                "IT&E Programmes": "programmes",
                "Learning Support Centre": "lsc",
                "Learning Technologies": "learning_technologies",
                "LT/LSC": "lt_lsc",
                "Unassigned": "unassigned",
            };
            var ownerPrefix = "projects:owner:";
            
            /**
             * Initialization function
             * 
             */
            var init = function()
            {   
                //create hashmap of projects, with department owners used as lookup id's 
                var projectsHash = {};
                vm.filterTabs = [{id: 'all', title: 'All'}];
                
                for(var i=0; i < projects.length; i++) {
                    var project = projects[i];
                    var projectOwner = projectOwnersLookup[project.department_owner];
                    
                    if(!projectsHash[projectOwner]) {
                        projectsHash[projectOwner] = [];
                        
                        //create the filter tab
                        vm.filterTabs.push({id: projectOwner, title: elgg.echo(ownerPrefix+projectOwner)});
                    }
                    projectsHash[projectOwner].push(project);
                }

                vm.projects = projects;
                vm.projectsHash = projectsHash;
            };
            
            init();
            
            /**
             * Filter projects in the datatable using hash table lookup
             * 
             */
            vm.filterProjects = function(key)
            {
                if(key == 'all') {
                    vm.projects = projects;
                    return;
                }
                vm.projects = vm.projectsHash[key];
            }
            
            /**
             * Toggle the 'active' class on filter tab <li> eleements
             * 
             */
            vm.toggleFilterTab = function(event)
            {
                var domElement = event.target;
                
                //remove old active element and add class to clicked element
                $(domElement).closest('ul').find('li.active').removeClass('active');
                $(domElement).parent().addClass('active');
            }
            
            vm.selectProject = function(project)
            {
                vm.project = project;
            }
            
        }
})();



