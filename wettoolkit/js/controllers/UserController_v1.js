(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('Users', Users);
	
	function Users(user, $q, $rootScope, $timeout) {
		var init = function() {
			user.getUsers().then(function(result){
				vm.users = result.data;
			}, function(error){
				console.log(error);
			});
		}
		
		init();
		
		var vm = this;
		
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