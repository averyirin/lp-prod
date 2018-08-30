(function(){
    'use strict';

    angular.module('portal').controller('TripReportCtrl', TripReportCtrl);

    function TripReportCtrl(reports, report, tripReportService, $rootScope, $state, $sce, DTOptionsBuilder, DatePicker, Rating) {
        //clear loading messages and tinymce editors
        $rootScope.isLoading = false;
        tinyMCE.remove();
        
        //declare object collections
        var vm = this;
        vm.reports = reports;
        vm.report = {};
        //deep copy, want to assign object by value
        angular.copy(report, vm.report);
        vm.report.editable = [];
        
        vm.rating = new Rating(vm.report.ratings, 5, $rootScope.user.id);
        vm.datePicker = new DatePicker();

        //make datatable default sorting by the fourth column(time-submitted)
		vm.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [[3, 'desc']]);
        
        vm.removeFromList = function(index, list) {
            vm.report[list].splice(index, 1);
            if(vm.report.id){
                vm.update(list);
            }
        }
        
        vm.addToAnnex = function() {
            var obj = {presentation:"", summary:""};
            addToList("annex", obj);
        }
        
        vm.addToTravellers = function() {
            var obj = {rank:"", name:"", org:"", email:""};
            addToList("travellers", obj);
        }
        
        vm.addToItems = function(){
            var obj = {body:"", opi:{name:"", email:""}};
            addToList("issues_of_concern", obj);
        }
        
        vm.addToListSingleAttr = function(list) {
            addToList(list, {body:""});
        }
        
        function addToList(list, attributes) {
            vm.report[list].push(attributes);
        }
        
        vm.createReport = function(isValid) {
            tinyMCE.triggerSave();
            
            $("body").on('submit', "form[name='tripReportForm']", function(){
                //strict contextual escaping work for mce editor fields
                for(var i=0;i<vm.report.discussions.length;i++) {
                    vm.report.discussions[i].body = $(this).find('#discussion-'+i).val();
                }
                for(var i=0;i<vm.report.annex.length;i++) {
                    vm.report.annex[i].summary = $(this).find('#item_summary-'+i).val();
                }
                for(var i=0;i<vm.report.issues_of_concern.length;i++) {
                    if(!vm.report.issues_of_concern[i].body) {
                        vm.report.issues_of_concern[i] = {body:""};
                    }
                }
                vm.report.purpose = $(this).find('#purpose').val();

                if(isValid) {
                    $rootScope.isLoading = true;
                    tripReportService.create({"trip_report":vm.report}).then(function(success){
                        $(window).scrollTop(0);
                        $state.go("view", {"id":success.id});
                    },function(error){
                        console.log(error);
                    });
                }
            });
        }
        
        vm.update = function(field) {
            tinyMCE.triggerSave();
            var data = {};
            
            //strict contextual escaping work for mce editor fields
            if(field=='discussions' || field=='annex'){
                data[field] = [];
                for(var i=0;i<vm.report[field].length;i++) {
                    data[field][i] = {};

                    if(field=='discussions'){
                        data[field][i].body = $('body').find('#discussion-'+i).val();
                    }
                    else if(field=='annex'){
                        data[field][i].presentation = vm.report[field][i].presentation;
                        data[field][i].summary = $('body').find('#item_summary-'+i).val();
                    }
                }
            }
            else if(field=='purpose') {
                data[field] = $('body').find('#purpose').val();
            }
            else{
                data[field] = vm.report[field];
            }

            tripReportService.update(data, vm.report.id).then(function(success){
                if(field=='discussions' || field=='annex'){
                    for(var i=0;i<data[field].length;i++) {
                        if(field=='discussions') {
                            vm.report[field][i].body = $sce.trustAsHtml(data[field][i].body);
                        }
                        else if(field=='annex') {
                            vm.report[field][i].summary = $sce.trustAsHtml(data[field][i].summary);
                        }
                    }
                }
                else if(field=='purpose') {
                    vm.report[field] = $sce.trustAsHtml(data[field]);
                }
            },function(error){
                console.log(error);
            });
        }
        
        vm.updateRating = function(star) {
            vm.rating.addRating(star, $rootScope.user.id);
            
            var rating = vm.rating.getLatestRating();
                
            tripReportService.addRating({rating: rating}, vm.report.id).then(function(data){
                vm.report.ratings = data.ratings;
                vm.rating.update();
            });
        }
        
        vm.delete = function(id, key) {
            tripReportService.remove(id).then(function(success){
                //remove from the data table
                vm.reports.splice(key, 1);
            },function(error){
                console.log(error);
            });
        }
        
        vm.filter = function(filter) {
            $rootScope.isLoading = true;
            
            var params = {};
            if(filter == 'mine') {
                params['owner_guid'] = $rootScope.user.id;
            }
            
            tripReportService.getReports(params).then(function(reports){
                vm.reports = reports;
                setActiveTab(filter);
                $rootScope.isLoading = false;
            }, function(error){
                console.log(error);
                $rootScope.isLoading = false;
            });
        }
        
        vm.toggleEditMode = function(event) {
            var value = event.target.attributes['data-id'].value;
            vm.report.editable[value] ? vm.report.editable[value] = false : vm.report.editable[value] = true;
        }
        
        
        vm.cancelEdit = function(field){
            vm.report[field] = report[field];
        }
        
        vm.displayLoadingScreen = function() {
            $rootScope.isLoading = true;
        }
        
        /**
         * helper function to set the active filter tab
         * 
         * @param {string} tab
         * @returns void
         */
        function setActiveTab(tab) {
            $("#"+tab).parent().siblings('.active').removeClass('active');
            $("[id='"+tab+"']").parent().addClass('active');
        }

    }
})();
(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('UserCtrl', UserCtrl);
	
	function UserCtrl(user, userObj, $q, $rootScope, $timeout, $location, $stateParams) {
        //clear possible error messages from previous views
        $rootScope.errorMessage = false;
        $rootScope.message = '';
		
		var vm = this;
        vm.user = userObj;
        if(userObj.data){
            vm.user = userObj.data.user; 
        }

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
        
        vm.validateUser = function() {
            user.update({
                'validated':true,
            }, vm.user.id).then(function(success){
                //update user collection
                vm.user.validated = true;
                $rootScope.message = 'User has been validated';
				$rootScope.successMessage = true;
				$timeout(function(){
					$rootScope.successMessage = false;
				},10000);
            }, function(error){
                console.log(error);
            });
        }
        
        vm.toggleUserStatus = function(status) {
            user.update({
                'deactivated':status
            }, vm.user.id).then(function(success){
               vm.user.deactivated = status;
            });
        }
	}
})();
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
(function(){
    'use strict';
    
    angular.module('portal').factory('tripReportService', tripReportService);
    
    function tripReportService($resource, $q) {
        var publicKey = localStorage.getItem('publicKey');
        if(!publicKey){
            publicKey = elgg.get_logged_in_user_guid();
        }
        
        function getReports(filter) {
            var params = {'public_key':publicKey};
			
			//check for a filter
			var filter = (typeof filter === 'undefined') ? null : filter;
			if(filter) {
				for (var key in filter) {
					if (filter.hasOwnProperty(key)) {
						params[key] = filter[key];
					}
				}
			}
			
			var queryString = angular.toJson(params);
			
			var TripReport = $resource('/portal/internapi/trip_reports', {},
				{
					'query':{
						'params':params,
					}
				}
			);

			return TripReport.query().$promise.then(function(results){
				return results.data.trip_reports;
			}, function(error){
				return error;
			});
        }
        
        function getReport(id) {
            var params = {'public_key': publicKey};
            
			var TripReport = $resource('/portal/internapi/trip_reports/:id',
				{id:'@id'},
				{
					"get":{
                        'params':params
					}
				}
			);
        
            return TripReport.get(null, {'id':id}).$promise.then(function(results){
                var trip_report =  results.data.trip_report;
                
                //check for empty collections
                var collections = ["travellers","opi","discussions","issues_of_concern","internal_to_sc","external_to_sc","annex"];
                for (var prop in trip_report) {
                    if(collections.indexOf(prop) >= 0) {
                        trip_report[prop] ? '' : trip_report[prop] = [{}];
                    }
                }
                
                trip_report.ratings ? '' : trip_report.ratings = [];
                
                return trip_report;
            }, function(error){
                return error;
            });
        }
        
        function create(data) {
            var params = {'public_key': publicKey};
            
            var TripReport = $resource('/portal/internapi/trip_reports',{},
                {
                    "save":{
                        method: "POST",
                        params: params
                    }
                }
            );
            
            return TripReport.save(data).$promise.then(function(results){
                return results.data.trip_report;
            }, function(error){
                return error; 
            });
        }
        
        function update(data, id) {
            //check for undefined values - need to pass them to server as null not undefined
            for(var key in data) {
                if(data.hasOwnProperty(key)){
                    var value = data[key];
                    value ? '': data[key] = null;
                }
            }
            
            var params = {'public_key': publicKey};
            var TripReport = $resource('/portal/internapi/trip_reports/:id',
                {id: "@id"},
                {
                    "update": {
                        method: 'PUT',
                        'params': params
                    }
                }
            );

            return TripReport.update({'id': id}, data).$promise.then(function(success){
                return success;
            }, function(error){
                return error;
            });
        }
        
        function addRating(data, id) {
            var params = {'public_key': publicKey};
            var TripReport = $resource('/portal/internapi/trip_reports/:id/ratings',
                {id: "@id"},
                {
                    "update": {
                        method: 'POST',
                        'params': params
                    }
                }
            );

            return TripReport.update({'id': id}, data).$promise.then(function(success){
                return success.data;
            }, function(error){
                return error;
            });
        }
        
        function remove(id) {
            var params = {'public_key':publicKey};

            var TripReport = $resource('/portal/internapi/trip_reports/:id',
                {id: "@id"},
                {
                    "delete": {
                        method: 'DELETE',
                        'params': params,
                    }
                }
            );
        
            return TripReport.delete({'id':id}).$promise.then(function(success){
                return success;
            }, function(error){
                return error;
            });
        }
        
        return{
            getReports: getReports,
            getReport: getReport,
            create: create,
            update: update,
            addRating: addRating,
            remove : remove
        }
    }
})();
(function(){
	'use strict';
	
	angular
		.module('portal')
		.factory('user', user);
		
	function user($resource, $q) {
		var publicKey = localStorage.getItem('publicKey');
		var privateKey = localStorage.getItem('privateKey');
		
		function getUsers(filter) {
			var params = {'public_key':publicKey};
			
			//check for a filter
			filter = (typeof filter === 'undefined') ? null : filter;
			if(filter) {
				for (var key in filter) {
					if (filter.hasOwnProperty(key)) {
						params[key] = filter[key];
					}
				}
			}
			
			var queryString = angular.toJson(params);
			
			var User = $resource('/portal/internapi/users', {},
				{
					'query':{
						'params':params,
					}
				}
			);

			return User.query().$promise.then(function(results){
				return results;
			}, function(error){
				return error;
			});
		}
		
		function getUser(id) {
			var params = {'public_key':publicKey};

			var User = $resource('/portal/internapi/users/:id',
				{id:'@id'},
				{
					"get":{
                        'params':params
					}
				}
			);
	
			return User.get(null, {'id':id}).$promise.then(function(result){
				return result;
			}, function(error){
				return $q.reject(error);
			});
		}
		
		function update(data,id) {
			data.public_key = publicKey;
			
			var queryString = angular.toJson(data);
			
			var User = $resource('/portal/internapi/users/:id',
				{id:'@id'},
				{
					"update":{
						method:"PUT",
					}
				}
			);
	
			return User.update({'id':id},data).$promise.then(function(results){
				return results;
			}, function(error){
				return $q.reject(error);
			});
		}
                
        function register(data) {
            var User = $resource('api/users');

            return User.save(data).$promise.then(function(results){
                return results;
            }, function(error){
                return $q.reject(error);
            });
        }

        function disable(id) {
            var User = $resource('api/users/:id/disable',
                {id:'@id'},
                {
                    "disable":
                        {
                            method:"PUT"
                        }
                }
            );

            return User.disable({'id':id}).$promise.then(function(results){
                return results;
            }, function(error){
                return $q.reject(error);
            });
        }
		
		return {
			getUsers: getUsers,
			getUser: getUser,
			update: update,
            register: register,
            disable: disable
		}
	}
})();
(function(){
	'use strict';
	
	angular
		.module('portal')
		.factory('notification', notification);

		function notification($resource, helper, $q) {
			var publicKey = localStorage.getItem('publicKey');
			var privateKey = localStorage.getItem('privateKey');
                        
                        if(!publicKey){
                            publicKey = elgg.get_logged_in_user_guid();
                        }
			
			function create(subject, body, toId) {
				var notificationSubject = subject;
				var notificationBody = body;

				var data = {'subject':notificationSubject, 'body':notificationBody, 'public_key':publicKey, 'to_id':toId};

				//sort params by alpha ASC
				var orderedParams = helper.orderParams(data);
				var queryString = angular.toJson(orderedParams);

				var Notification = $resource('internapi/notifications', 
					{},
					{
						"save":{
							method:'POST',
						}
					}
				);

				return Notification.save(data).$promise.then(function(result){
					return result;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			return{
				create: create
			}
		}
})();
var DatePicker = function() {
        this.dateOptions = {
            formatYear: 'yy',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };
        this.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        this.format = this.formats[0];
        this.altInputFormats = ['M!/d!/yyyy'];
        this.popup1 = {};
        this.popup2 = {};
        
        this.open1 = function() {
            this.popup1.opened = true;
        }
        
        this.open2 = function() {
            this.popup2.opened = true;
        }
        
}

angular.module('portal').factory('DatePicker', function(){
    return DatePicker;
});
(function(){
    'use strict';
    
    angular.module('portal').factory('Stars', function(){
        
        function Stars(value, maxValue) {
            this.stars = [];
            this.value = value;
            this.maxValue = maxValue;
            
            this.createStars();
        }
        
        Stars.prototype.createStars = function() {
            for(var i=0; i < this.maxValue; i++) {
                this.addStar({filled: i < this.value});
            }
        }
        
        Stars.prototype.addStar = function(star) {
            this.stars.push(star);
        };
        
        return Stars;
    });
})();
(function(){
    'use strict';
    
    angular.module('portal').factory('Rating', function(Stars){
        
        function Rating(ratings, maxValue, userId) {
            ratings ? this.ratings = ratings : this.ratings=[];
            this.stars = [];
            this.maxValue = maxValue;
            this.userId = userId;
            this.totalValue = 0;
            this.numOfRatings = 0;
            
            this.createRating();
        }
        
        Rating.prototype.createRating = function() {
            var userValue = 0;
            
            if(this.ratings.length >= 1) {
                var totalRatings = 0;

                for(var i=0; i < this.ratings.length; i++) {
                    var rating = this.ratings[i];
                    totalRatings += rating.value;
                    this.numOfRatings += 1;
                    
                    if(rating.user == this.userId) {
                        userValue = rating.value;
                    }
                }

                this.totalValue = totalRatings/this.numOfRatings;
            }
            
            var stars = new Stars(userValue, this.maxValue);
            this.stars = stars.stars;
        };
        
        Rating.prototype.update = function() {
            this.empty();
            this.createRating();
        };
        
        Rating.prototype.addRating = function(star, userId) {
            var index = this.stars.indexOf(star);
            var value = index + 1;
            
            for(var i=0; i < this.ratings.length; i++) {
                var rating = this.ratings[i];
                
                if(userId == rating.user) {
                    this.ratings.splice(i, 1);
                    i -= 1;
                }
            }
            
            var rating = {value: value, user: userId}
            this.ratings.push(rating);
        }
        
        Rating.prototype.getLatestRating = function() {
            var rating = this.ratings[this.ratings.length -1];
            
            return rating;
        }
        
        Rating.prototype.empty = function() {
            this.totalValue = 0;
            this.userValue = 0;
            this.numOfRatings = 0;
            this.stars = [];
        }
        
        return Rating;
    });
})();