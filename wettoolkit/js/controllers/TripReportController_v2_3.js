(function(){
    'use strict';

    angular.module('portal').controller('TripReportCtrl', TripReportCtrl);

    function TripReportCtrl(reports, report, tripReportService, $rootScope, $state, $sce, DTOptionsBuilder, DatePicker) {
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
        
        vm.datePicker = new DatePicker();

        //make datatable default sorting by the fourth column(time-submitted)
		vm.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [[3, 'desc']]);
        
        vm.removeFromList = function(index, list) {
            vm.report[list].splice(index, 1);
            if(vm.report.id){
                vm.update(list);
            }
        }
        
        vm.addToList = function(list) {
            vm.report[list].push({});
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
        
        vm.delete = function(id, key) {
            tripReportService.remove(id).then(function(success){
                //remove from the data table
                vm.reports.splice(key, 1);
            },function(error){
                console.log(error);
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

    }
})();