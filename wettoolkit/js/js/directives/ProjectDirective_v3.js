(function() {
	'use strict';

    angular
		.module('portal')
		.directive('ngConfirmationNeeded', function () {
            return {
                priority: 1,
                terminal: true,
                link: function (scope, element, attr) {
                    var msg = attr.ngConfirmationNeeded || "Are you sure?";
                    var clickAction = attr.ngClick;
                    element.bind('click',function () {
                        if ( window.confirm(msg) ) {
                            scope.$eval(clickAction);
                        }
                    });
                }
            };            
        });
		
	angular
		.module('portal')
		.directive('ngFocusError', function () {
            return {
                restrict: "A",
                link: function (scope, elem) {
                    // set up event handler on the form element
					elem.on('submit', function () {
						// find the first invalid element
						elem.find('.ng-invalid:first').focus();
					});
                }
            };            
        });

	angular
		.module('portal')
		.directive('ngDeleteOnce', function () {
            return {
                priority: 1,
                terminal: true,
                link: function (scope, element, attr) {
                    var msg = attr.ngDeleteOnce || "Are you sure?";
                    var clickAction = attr.ngClick;
                    element.bind('click',function () {
                        if ( window.confirm(msg) ) {
                            scope.$eval(clickAction);
                            $(this).attr('disabled', true);
                        }
                    });
                }
            };           
        });

})();