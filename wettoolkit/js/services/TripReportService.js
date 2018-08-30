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