(function(){
	'use strict';
	
	angular
		.module('portal', [
			'ngResource',
			'ngRoute',
			'ngFileUpload',
			'ngMessages',
			'ngAnimate',
			'datatables',
			'ui.slider',
			'ui.bootstrap',
		]);
		
	angular
		.module('portal')
		.config(function($routeProvider) {
			$routeProvider.when('/projects',{
				templateUrl: 'projects/list',
				controller: 'Projects as vm'
			}).
			when('/projects/create',{
				templateUrl: 'projects/add',
				controller: 'Projects as vm'
			}).
			when('/projects/view/:project_id',{
				templateUrl: function(params){return 'projects/view/'+params.project_id;},
				controller: 'Projects as vm'
			}).
			when('/projects/edit/:project_id',{
				templateUrl: function(params){return 'projects/edit/'+params.project_id;},
				controller: 'Projects as vm'
			}).
            when('/projects/delete/:project_id',{
				templateUrl: function(params){return 'projects/delete/'+params.project_id;},
				controller: 'Projects as vm'
			}).
			when('/projects/create_admin',{
				templateUrl: 'projects/add_admin',
				controller: 'UsersCtrl as vm',
                resolve: {
                    users : function(user) {
                        return user.getUsers();
                    }
                }
			}).
			otherwise({
				redirectTo: '/projects'
			});
		});
	
	angular.module('portal').run(function($rootScope, user){
		$rootScope.isLoading = false;
		$rootScope.successMessage = false;
		$rootScope.message = '';
		
		var paramObject = new Object();
		user.getUser(paramObject, localStorage.getItem('publicKey')).then(function(result){
			$rootScope.user = result.data;
		}, function(error){
			console.log(error);
		});
	});
})();