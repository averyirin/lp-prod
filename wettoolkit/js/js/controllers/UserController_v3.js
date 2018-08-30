(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('UserCtrl', UserCtrl);
	
	function UserCtrl(user, $q, $rootScope, $timeout, $location) {
        //clear possible error messages from previous views
        $rootScope.errorMessage = false;
        $rootScope.message = '';
		
		var vm = this;
        vm.user = {};
		
		/*
		 * scope functions
		 */
                
        vm.registerUser = function(isValid) {
            if(vm.user.email != vm.email_verification){
                isValid = false;
            }

            if(isValid){
                $rootScope.isLoading = true;

                user.register(vm.user).then(function(result){
                    user.disable(result.data.id).then(function(data) {
                        $rootScope.isLoading = false;
                        $location.path('users/confirm/'+data.data.id+'?email='+data.data.email+'&name='+data.data.name);
                    }, function(error){
                        console.log(error);
                        $rootScope.isLoading = false;
                    });

                }, function(error){
                    //clear loading overlay
                    $rootScope.isLoading = false;
                    //display error message
                    var messages = error.data.data;
                    $rootScope.message = messages[0];
                    $rootScope.errorMessage = true;
                });
            }
            else{
                //not valid
            }
        }
                
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