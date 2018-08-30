(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('MainCtrl', MainCtrl);

		function MainCtrl($scope) {
            $scope.$on('$routeChangeStart', function() {
                $scope.isViewLoading = true;
            });
            
            $scope.$on('$routeChangeSuccess', function() {
                $scope.isViewLoading = false;
            });
            $scope.$on('$routeChangeError', function() {
                $scope.isViewLoading = false;
            });
        }
})();