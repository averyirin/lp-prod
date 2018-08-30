(function(){
    'use strict';
    
    angular
        .module('portal')
        .directive('emailValidation', function(){
           return {
               require: 'ngModel',
               link: function(scope, elem, attr, ngModel) {
                   var validDomains = ['forces.gc.ca', 'canada.ca'];
                   
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
})();