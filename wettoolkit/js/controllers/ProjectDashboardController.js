(function() {
    'use strict';

    angular
        .module('portal')
  	.filter('htmlToPlaintext', function() {    	 
    		return function(text) {
      			return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    		};
  	})



        .controller('ProjectsDashboard', ProjectsDashboard);

    function ProjectsDashboard(resolveProject, project, Upload, $rootScope, $q, $sce) {

	

        var vm = this;
        vm.projects = [];
        vm.project = false;
	vm.key = 'all';

        vm.filters = {
            archived: '',
		status: ''
        };
        vm.filters.archived = 'false';

        var projectOwnersLookup = {
            "IT&E Modernization": "modernization",
            "Learning Support Centre": "lsc",
            "RCAF Learning Support Centre": "alsc",
            "Unassigned": "undefined",

        };
        var ownerPrefix = "projects:owner:";
      
        getProjects(vm.filters);

        function getProjects(params) {
            return $q(function(resolve, reject) {
                project.getProjects(params).then(function(results) {
                    vm.projects = results.data;
                    resolve();
                    init();
		    vm.toggleFilterTab();			
		    vm.filterProjects(vm.key);
                }, function(error) {
                    reject(error);
                });
            });
        }
	
	function htmlToPlainText(text){
      			return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
	}

        vm.filter = function(event) {
            var filter = $(event.target).attr('id');
            var filterType = $(event.target).attr('data-filter-type');

if($(event.target).hasClass('list-status')){
          $('.list-status').removeClass('active');
}
if($(event.target).hasClass('list-collection')){
          $('.list-collection').removeClass('active');
}



           $(event.target).addClass('active');



            vm.filters[filterType] = filter;
            getProjects(vm.filters);
        }


        vm.archiveProject = function(id) {
            Upload.upload({
                url: 'internapi/projects',
                data: {
                    'projectId': id,
                    'action': 'archiveProject'
                }
            }).then(function(success) {
                getProjects(vm.filters);

            }, function(error) {
                console.log(error);
            });
        }
        vm.unarchiveProject = function(id) {
            Upload.upload({
                url: 'internapi/projects',
                data: {
                    'projectId': id,
                    'action': 'unarchiveProject'
                }
            }).then(function(success) {                
		getProjects(vm.filters);
            }, function(error) {
                console.log(error);
            });

        }

	
        /**
             * Initialization function
           
             */

        function init() {
            //create hashmap of projects, with department owners used as lookup id's 
            var projectsHash = {};
            vm.filterTabs = [{
                id: 'all',
                title: 'All'
            }];

            for (var i = 0; i < vm.projects.length; i++) {
                var project = vm.projects[i];

		var charMax = 300;
		project.cleanDescription =  (project.description.length > charMax) ? 
					$sce.trustAsHtml(htmlToPlainText(project.description).substring(0,charMax)+"...")
			 		: $sce.trustAsHtml(htmlToPlainText(project.description));
		project.description =  $sce.trustAsHtml(project.description);

                var projectOwner = projectOwnersLookup[project.department_owner];

                if (!projectsHash[projectOwner]) {
                    projectsHash[projectOwner] = [];

                    //create the filter tab
                    vm.filterTabs.push({
                        id: projectOwner,
                        title: elgg.echo(ownerPrefix + projectOwner)
                    });
                }
                projectsHash[projectOwner].push(project);
            }

            vm.allProjects = vm.projects;
            vm.projectsHash = projectsHash;


        }

        /**
         * Filter projects in the datatable using hash table lookup
         * 
         */
        vm.filterProjects = function(key) {
		vm.key = key;
            if (vm.key== 'all') {
                vm.projects = vm.allProjects;
                return;
            }

            vm.projects = vm.projectsHash[vm.key];
        }

        /**
         * Toggle the 'active' class on filter tab <li> eleements
         * 
         */
        vm.toggleFilterTab = function() {
		
		var t = "a#"+vm.key+".ng-binding";
		
		$(t).parent().siblings().removeClass('active');
		$(t).parent().addClass('active');
		$(t).addClass('active');
        }

        vm.selectProject = function(project) {
            vm.project = project;
        }

    }
})();