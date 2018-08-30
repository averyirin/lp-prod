(function(){
	'use strict';
	
	angular
		.module('portal')
		.factory('EntityService', EntityService);
		
	function EntityService($resource, $q, Upload) {
		var publicKey = localStorage.getItem('publicKey');
        if(!publicKey){
            publicKey = elgg.get_logged_in_user_guid();
        }
		
        function getEntity(id) {
            var Entity = $resource('/portal/internapi/entity/:id', 
                {id: "@id"}, 
                {
                    "get": {
                        'params':{'public_key':publicKey},
                    }
                }
            );

            return Entity.get({}, {'id': id}).$promise.then(function(results) {
                return results;
            }, function(error){
                return $q.reject(error);
            });
        }
		return {
           getEntity: getEntity,
		}
	}
})();