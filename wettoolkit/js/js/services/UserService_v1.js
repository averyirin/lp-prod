(function(){
	'use strict';
	
	angular
		.module('portal')
		.factory('user', user);
		
	function user($resource, helper, $q) {
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
			
			//sort params by alpha ASC
			var orderedParams = helper.orderParams(params);
			
			var queryString = angular.toJson(orderedParams);
			var signature = helper.createSignature(queryString,publicKey,privateKey);
			
			var User = $resource('api/users', {},
				{
					'query':{
						'params':orderedParams,
						'headers':{'Signature':signature}
					}
				}
			);

			return User.query().$promise.then(function(results){
				return results;
			}, function(error){
				return error;
			});
		}
		
		function getUser(data, id) {
			data.public_key = publicKey;
			
			var queryString = angular.toJson(data);
			var signature = helper.createSignature(queryString,publicKey,privateKey);
			
			var User = $resource('api/users/:id',
				{id:'@id'},
				{
					"get":{
						'headers':{'Signature':signature}
					}
				}
			);
	
			return User.get(data, {'id':id}).$promise.then(function(result){
				return result;
			}, function(error){
				return $q.reject(error);
			});
		}
		
		function update(data,id) {
			data.public_key = publicKey;
			
			var queryString = angular.toJson(data);
			var signature = helper.createSignature(queryString,publicKey,privateKey);
			
			var User = $resource('api/users/:id',
				{id:'@id'},
				{
					"update":{
						method:"PUT",
						'headers':{'Signature':signature}
					}
				}
			);
	
			return User.update({'id':id},data).$promise.then(function(results){
				return results;
			}, function(error){
				return $q.reject(error);
			});
		}
		
		return {
			getUsers: getUsers,
			getUser: getUser,
			update: update,
		}
	}
})();