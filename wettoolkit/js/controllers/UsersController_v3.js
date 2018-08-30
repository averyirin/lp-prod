(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('UsersCtrl', UsersCtrl);
	
	function UsersCtrl(user, users, $q, $rootScope, $timeout, $location) {
        //clear possible error messages from previous views
        $rootScope.errorMessage = false;
        $rootScope.message = '';
		var vm = this;
        vm.users = users.data;
        
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