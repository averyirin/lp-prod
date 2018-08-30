(function(){
	'use strict';
	
	angular
		.module('portal', [
			'ngResource',
			'ngRoute',
			'ngFileUpload',
			'ngMessages',
			'ngAnimate',
			'ui.slider',
			'ui.bootstrap',
		]);
        
	angular.module('portal').run(function($rootScope, user){
		$rootScope.isLoading = false;
		$rootScope.successMessage = false;
		$rootScope.message = '';

        var publicKey = localStorage.getItem('publicKey');
        if(!publicKey){
            publicKey = elgg.get_logged_in_user_guid();
        }
		user.getUser(publicKey).then(function(result){
			$rootScope.user = result.data.user;
		}, function(error){
			console.log(error);
		});
	});
    
})();