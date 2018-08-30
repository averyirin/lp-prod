(function(){
	'use strict';
	
	angular
		.module('portal', [
			'ngResource',
			'ui.router',
			'ngRoute',
			'ngMessages',
			'ngAnimate',
			'datatables',
			'ui.slider',
			'ui.bootstrap',
		]);
		
	angular
		.module('portal')
		.config(function($routeProvider) {
			$routeProvider.when('/register',{
				templateUrl: 'users/register',
				controller: 'UserCtrl as vm',
			}).
            when('/users/confirm/:id',{
               templateUrl: function(params){return 'users/confirm/'+params.id;},
               controller: 'UserCtrl as vm',
            }).
			otherwise({
				redirectTo: '/register'
			});
		});
	
	angular.module('portal').run(function($rootScope){
		$rootScope.isLoading = false;
		$rootScope.successMessage = false;
        $rootScope.errorMessage = false;
		$rootScope.message = '';
	});
})();