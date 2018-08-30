(function() {
	'use strict';

	angular
		.module('portal')
		.factory('project', project);

		function project($resource, helper, $q) {
			
			function getProject(data, id) {
				var publicKey = localStorage.getItem('publicKey');
				var privateKey = localStorage.getItem('privateKey');
				
				data.public_key = publicKey;
				var queryString = angular.toJson(data);

				var Project = $resource('internapi/projects/:id', 
					{id: "@id"}, 
					{
						"get": {
							'params':{'public_key':publicKey},
						}
					}
				);
		
				return Project.get({}, {'id': id}).$promise.then(function(results) {
					return results;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			function getProjects(publicKey, filter) {
				var params = {'public_key':publicKey};
				
				filter = (typeof filter === 'undefined') ? null : filter;
				if(filter) {
					for (var key in filter) {
						if (filter.hasOwnProperty(key)) {
							params[key] = filter[key];
						}
					}
				}
				
				var Project = $resource('internapi/projects/:id', 
					{}, 
					{
						"query": {
							'params':params
						}
					}
				);
		
				return Project.query().$promise.then(function(results) {
					return results;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			function create(data) {
				data.user_id = parseInt(localStorage.getItem('publicKey'));
				//stringify JSON 
				var queryString = angular.toJson(data);
				var publicKey = localStorage.getItem('publicKey');
				
				var Project = $resource('internapi/projects/:id', 
					{}, 
					{
						"save": {
							method:'POST',
							'params':{'public_key':publicKey},
						}
					}
				);

				return Project.save(data).$promise.then(function(success) {
					return success;
				}, function(error) {
					return $q.reject(error);
				});
			}
			
			function edit(data, id) {
				data.user_id = parseInt(localStorage.getItem('publicKey'));
				//stringify JSON 
				var queryString = angular.toJson(data);
				var publicKey = localStorage.getItem('publicKey');
				var privateKey = localStorage.getItem('privateKey');
				
				var Project = $resource('internapi/projects/:id',
					{id: "@id"},
					{
						"save": {
							method:'POST',
							'params':{'public_key':publicKey},
						}
					}
				);
		
				return Project.save({'id':id},data).$promise.then(function(success){
					return success;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			function update(data, id) {
				//stringify JSON 
				var queryString = angular.toJson(data);
				//create signature
				var publicKey = localStorage.getItem('publicKey');
				var privateKey = localStorage.getItem('privateKey');
				
				var Project = $resource('internapi/projects/:id', 
					{id: "@id"}, 
					{
						"update": {
							method:'PUT',
							'params':{'public_key':publicKey},
						}
					}
				);
		
				return Project.update({'id': id},data).$promise.then(function(success){
					return success;
				}, function(error){
					return $q.reject(error);
				});
			}

			function remove(data, id) {
				var publicKey = localStorage.getItem('publicKey');
				var privateKey = localStorage.getItem('privateKey');
				
				data.public_key = publicKey;
				var queryString = angular.toJson(data);

				var Project = $resource('internapi/projects/:id',
					{id: "@id"}, 
					{
						"remove": {
							method:'DELETE',
							'params':{'public_key':publicKey},
						}
					}
				);

				return Project.remove({'id': id}, data).$promise.then(function(success){
					return success;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			return {
				getProject: getProject,
				getProjects: getProjects,
				create : create,
				edit : edit,
				update : update,
				remove : remove
			}
		}	

	angular
		.module('portal')
		.service('helper', helper);

		function helper() {
			function createSignature(queryString,publicKey,sharedSecret){
				var hashedQS = CryptoJS.SHA1(queryString).toString();
				var privateKey = sharedSecret;

				var hash = CryptoJS.HmacSHA256(hashedQS,privateKey).toString();

				return hash;
			}
			
			function orderParams(params) {
				var keys = [];
				var orderedObject = {};
				
				for(var k in params) {
					if(params.hasOwnProperty(k)) {
						keys.push(k);
					}
				}
				keys.sort();
				for(var i=0; i < keys.length; i++) {
					k = keys[i];
					orderedObject[k] = params[k];
				}
				
				return orderedObject;
			}
			
			return {
				createSignature : createSignature,
				orderParams : orderParams
			}
		};
		
	/*
	 * bootstrapper for language files
	 */
	angular
		.module('portal')
		.service('languageLoader', languageLoader);

		function languageLoader(helper, $resource, $q) {
			
			function getLocalization(publicKey, plugin) {
				var params = {'public_key':publicKey};
				var queryString = angular.toJson(params);
				var signature = helper.createSignature(queryString,publicKey);

				var Language = $resource('api/language/:plugin/', 
					{plugin: "@plugin"}, 
					{
						"get": {
							'params':{'public_key':publicKey},
							'headers':{'Signature':signature}
						}
					}
				);
		
				return Language.get({}, {'plugin': plugin}).$promise.then(function(results) {
					return results;
				}, function(error){
					return $q.reject(error);
				});
			}
			
			return {
				getLocalization : getLocalization
			}	
		};
})();