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