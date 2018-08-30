(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('UsersCtrl', UsersCtrl);
	
	function UsersCtrl(user, users, $q, $rootScope, $timeout, $location, DTOptionsBuilder) {
        //clear possible error messages from previous views
        $rootScope.errorMessage = false;
        $rootScope.message = '';
		var vm = this;
        vm.users = users.data;
        //define project types
        vm.projectTypes = {"values":[elgg.echo('projects:types:courseware'), elgg.echo('projects:types:enterprise_apps'),elgg.echo('projects:types:instructor_support'),elgg.echo('projects:types:learning_application'),elgg.echo('projects:types:learning_technologies'),
			elgg.echo('projects:types:mobile'),elgg.echo('projects:types:modelling'), elgg.echo('projects:types:rnd'), elgg.echo('projects:types:gaming'),elgg.echo('projects:types:support')]};
        
        //make datatable default sorting by the fourth column(time-submitted)
        vm.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [[0, 'desc']]);
        
		/*
		 * helper functions
		 */
		function getProjectAdminUsers() {
			var filter = {'project_admin':true};
			user.getUsers(filter).then(function(result){
				vm.users = result.data;
			}, function(error){
				console.log(error);
			});
		}
		
        /*
         * scope functions
         */
        vm.addProjectAdmin = function(userId) {
          user.update({
            'project_admin':true,
            'project_types': vm.types,
          },userId).then(function(success){
            vm.selected_user = false;
            $('#name').val('');

            $rootScope.message = 'User has been added as a project admin';
            $rootScope.successMessage = true;
            $timeout(function(){
              $rootScope.successMessage = false;
            },5000);
          },function(error){
            console.log(error);
          });
        }
	}
})();