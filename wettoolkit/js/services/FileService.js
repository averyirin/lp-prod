(function(){
	'use strict';
	
	angular
		.module('portal')
		.factory('FileService', FileService);
		
	function FileService($resource, $q, Upload) {
		var publicKey = localStorage.getItem('publicKey');
        if(!publicKey){
            publicKey = elgg.get_logged_in_user_guid();
        }
		
        function create(file) {
            return Upload.upload({
                url: '/portal/internapi/files',
                data: {file: file.file, fileData:file, auth:{publicKey:publicKey}}
            }).progress(function(prog){
                return prog;
            })
            .success(function(success){
                return success;
            }, function(error){
                return error;
            });
        }
		return {
           create: create,
		}
	}
})();