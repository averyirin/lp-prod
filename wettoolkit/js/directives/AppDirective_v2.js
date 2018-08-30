(function(){
    'use strict';
    
    angular
        .module('portal')
        .directive('emailValidation', function(){
           return {
               require: 'ngModel',
               link: function(scope, elem, attr, ngModel) {
                   var validDomains = ['forces.gc.ca', 'canada.ca', 'tpsgc-pwgsc.ca'];
                   
                   ngModel.$parsers.unshift(function(value) {
                       var domain = value.substring(value.lastIndexOf('@')+1);
                       var valid = validDomains.indexOf(domain)>= 0;
                       
                       ngModel.$setValidity('emailValidation', valid);
                       return value;
                   });
               }
           }
        });
        
    angular
        .module('portal')
        .directive('passwordValidation', function(){
           return {
               require: 'ngModel',
               link: function(scope, elem, attr, ngModel) {
                   ngModel.$parsers.unshift(function(value) {
                       //password must contain one uppercase character, one lowercase character, and one number 
                       var match = value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/);
                       var valid = false;
                       
                       if(match && value.length >= 8) {
                           var valid = true;
                       }
                       ngModel.$setValidity('passwordValidation', valid);
                       
                       return value;
                   });
               }
           } 
        });
        
    angular
        .module('portal')
        .directive('scrollToError', function(){
            return {
                restrict: "A",
                link: function(scope, elem) {
                    elem.on('submit', function(){
                        var offset = $('#ng-app').offset().top;

                        $('html, body').animate({
                            scrollTop: offset
                        }, 500);
                    });
                }
            }
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

    angular.module('portal').directive('mceInit', ['$timeout',function($timeout) {
        return{
            link: function($scope, element, attrs) {
                $timeout(function(){
                    tinymce.suffix = '.min';
                    tinymce.init({
                        selector : "#"+attrs.id,
                        theme: 'modern',
                        schema:'html5',
                        plugins: [
                            'advlist link image charmap print preview hr anchor pagebreak',
                            'searchreplace wordcount visualblocks visualchars code fullscreen',
                            'insertdatetime media nonbreaking save table contextmenu directionality',
                            'emoticons template paste textcolor colorpicker textpattern imagetools jbimages jbvideos'
                        ],
                        relative_urls : false,
                        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                        toolbar2: 'print preview media | forecolor backcolor emoticons imagetools jbimages jbvideos',
                        image_advtab: true,
                        width : "100%",
                        extended_valid_elements : "*[*]",
                        remove_script_host : false,
                        document_base_url : elgg.config.wwwroot,
                    });
                },1000);
            }
        };
    }]);

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