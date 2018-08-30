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
        
        vm.highlightStars = function(star) {
            vm.rating.stars.highlightStars(star);
        }
        
        vm.unhighlightStars = function() {
            vm.rating.stars.unhighlightStars();
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