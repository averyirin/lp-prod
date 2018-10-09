(function() {
    'use strict';

    angular
        .module('portal')
  	.filter('htmlToPlaintext', function() {    	 
    		return function(text) {
      			return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    		};
  	})



        .controller('ProjectsDashboard', ProjectsDashboard);

    function ProjectsDashboard(resolveProject, project, Upload, $rootScope, $q, $sce) {

	

        var vm = this;
        vm.projects = [];
        vm.project = false;
	vm.key = 'all';

	

        vm.filters = {
            archived: '',
		status: ''
        };

        vm.filters.archived = 'false';

        var projectOwnersLookup = {
            "IT&E Modernization": "modernization",
            "Learning Support Centre": "lsc",
            "RCAF Learning Support Centre": "alsc",
            "Unassigned": "undefined",

        };
     var revProjectOwnersLookup = {
             "modernization" : "IT&E Modernization",
            "lsc" : "Learning Support Centre",
             "alsc" : "RCAF Learning Support Centre",
             "undefined" : "Unassigned"

        };

        var ownerPrefix = "projects:owner:";
      
        getProjects(vm.filters);

        function getProjects(params) {
            return $q(function(resolve, reject) {
                project.getProjects(params).then(function(results) {
                    vm.projects = results.data;
                    resolve();
                    init();
		    vm.toggleFilterTab();			
		    vm.filterProjects(vm.key);
                }, function(error) {
                    reject(error);
                });
            });
        }
	
	function htmlToPlainText(text){
      			return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
	}

        vm.filter = function(event) {
            var filter = $(event.target).attr('id');
            var filterType = $(event.target).attr('data-filter-type');

if($(event.target).hasClass('list-status')){
          $('.list-status').removeClass('active');
}
if($(event.target).hasClass('list-collection')){
          $('.list-collection').removeClass('active');
}



           $(event.target).addClass('active');



            vm.filters[filterType] = filter;
            getProjects(vm.filters);
        }


        vm.archiveProject = function(id) {
            Upload.upload({
                url: 'internapi/projects',
                data: {
                    'projectId': id,
                    'action': 'archiveProject'
                }
            }).then(function(success) {
                getProjects(vm.filters);

            }, function(error) {
                console.log(error);
            });
        }
        vm.unarchiveProject = function(id) {
            Upload.upload({
                url: 'internapi/projects',
                data: {
                    'projectId': id,
                    'action': 'unarchiveProject'
                }
            }).then(function(success) {                
		getProjects(vm.filters);
            }, function(error) {
                console.log(error);
            });

        }

	vm.exportBtnSetActive = function() {
           $("#confluenceAlreadyBtn").attr('id', '');
	}
	
	vm.exportBtnSetInactive = function (event){
	console.log(event);
		$(event.target).attr('id', 'confluenceAlreadyBtn');	
	}


	vm.exportRequests = function(){

 		//Get the formatted HTML Project Charter information

		
		vm.generateSupportRequestsExport();
    	


  var spaceKey = "SRR";
	    var pageTitle = "2018/10/02";

            //display loading overlay
            $rootScope.isLoading = true;
            //create the charter in confluence

	     var reportTitle = (vm.key == 'all') ? 'All' :  (vm.key == 'lsc') ? 'LSC' : (vm.key == 'alsc')? 'RCAF LSC' : 'Unassigned';
	    var subtitle = (vm.filters.status == '') ? '' : ' '+vm.filters.status; 
		reportTitle = reportTitle + subtitle;
	    var archived = (vm.filters.archived == 'false') ? '' : 'Archived ';
	         reportTitle = archived + reportTitle;
 


            project.uploadToConfluence(reportTitle , "export", spaceKey, vm.projectPage, vm.taskPage, vm.overviewPage, vm.unassignedPage).then(function (result) {
                //sets the btn to be "view in confluence"
		console.log(result.url);
		vm.reportUrl = result.url;
		vm.exportBtnSetActive();

                // getSpaceInConfluence(vm);
                //show successful message
                $rootScope.isLoading = false;
                $rootScope.successMessage = true;
                $rootScope.message = result.data;

            }, function (error) {
                //show error message
                $rootScope.isLoading = false;
                $rootScope.errorMessage = true;
                $rootScope.message = error.data.data;
            });

}


	function compareValues(key, order) {
	order = (order == undefined) ? 'asc' : order;
  return function(a, b) {
    if(!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
      // property doesn't exist on either object
        return 0; 
    }

    const varA = (typeof a[key] === 'string') ? 
      a[key].toUpperCase() : a[key];
    const varB = (typeof b[key] === 'string') ? 
      b[key].toUpperCase() : b[key];

    let comparison = 0;
    if (varA > varB) {
      comparison = 1;
    } else if (varA < varB) {
      comparison = -1;
    }
    return (
      (order == 'desc') ? (comparison * -1) : comparison
    );
  };
}

	vm.generateSupportRequestsExport = function(){

    	var deferred = new $.Deferred();
	var projectPage = "";
	var taskPage = "";
	var overviewPage = "";
	var unassignedPage = "";

	var tsubmittedTotal = 0;
	var psubmittedTotal = 0;
	var usubmittedTotal = 0;


	var tinProgressTotal = 0;
	var pinProgressTotal = 0;	
	var uinProgressTotal = 0;

	var tcancelledTotal = 0;
	var pcancelledTotal = 0;
	var ucancelledTotal = 0;

	var tcompletedTotal = 0;
	var pcompletedTotal = 0;
	var ucompletedTotal = 0;


	var tunderReviewTotal= 0;
	var punderReviewTotal= 0;
	var uunderReviewTotal= 0;



	var supportRequests = vm.projects;
	
	var allProjects = [];
	var allTasks = [];
	var allUnassigned = [];

	supportRequests.forEach(function(sr) {	
			if(sr.classification == "Task"){
				allTasks.push(sr);
			}else if(sr.classification == "Project"){
				allProjects.push(sr);

			}else{
				allUnassigned.push(sr);
			}
	});
				console.log(allTasks);
				console.log(allProjects);
				console.log(allUnassigned);


	//Projects
	var projectsTable = '<table class="confluenceTable break" style="width:100%;"><colgroup><col style="width:60%;"></col><col></col><col></col><col></col><col></col><col></col></colgroup><tbody>';
		projectsTable += 	'<tr><th class="confluenceTh"  style="width:60%;">Title</th><th class="confluenceTh">Client Org</th><th class="confluenceTh">Training Authority</th><th class="confluenceTh">% Complete</th><th class="confluenceTh">Status</th><th class="confluenceTh">Department</th></tr>';
		allProjects.sort(compareValues('status'));

	allProjects.forEach(function(project) {	

			if(project.status == "Submitted"){
				psubmittedTotal ++;				
			}else if(project.status == "Under Review"){
				punderReviewTotal ++;				
			}else if(project.status == "In Progress"){
				pinProgressTotal ++;				
			}else if(project.status == "Completed"){
				pcompletedTotal ++;				
			}else if(project.status == "Cancelled"){
				pcancelledTotal ++;				
			}		

		var t = htmlToPlainText(project['description']);	
		t = t.replace(/;/g, "");		
		t = t.replace(/&/g, "");
		t = t.replace(/nbsp/g, " ");
		t = (t.length > 300) ? t.substring(0,300)+'...' : t;

		 var 	cleanDescription =  $("<div/>").html(t).text();
		 var cleanOrg = project['org'].replace(/&/g,"");
		 var cleanTa = project['ta'].replace(/&/g,"");
		 var cleanDO = project['department_owner'].replace(/&/g,"");


			projectsTable += 	'<tr><td colspan="1" class="confluenceTd">'+ '<a href="https://lp-pa.forces.gc.ca/portal/projects#/projects/view/'+ project['id'] + '">'+ project['title'] +'</a><br/><span>'+cleanDescription+'</span></td>';
			projectsTable += 	'<td colspan="1" class="confluenceTd">'+ cleanOrg  +'</td>';
			projectsTable += 	'<td colspan="1" class="confluenceTd">'+ cleanTa +'</td>';
			projectsTable += 	'<td colspan="1" class="confluenceTd">'+  project['percentage'] +'</td>';
			projectsTable += 	'<td colspan="1" class="confluenceTd">'+ project['status'] +'</td>';
			projectsTable += 	'<td colspan="1" class="confluenceTd">'+ cleanDO  +'</td>';
			projectsTable += '</tr>';
		
		});
		projectsTable += 	'</tbody></table>';
 		projectPage += projectsTable;	

	

		//Tasks
		var tasksTable = '<table class="confluenceTable break" style="width:100%;"><colgroup><col style="width:60%;"></col><col></col><col></col><col></col><col></col><col></col></colgroup><tbody>';
		tasksTable += 	'<tr><th class="confluenceTh"  style="width:60%;">Title</th><th class="confluenceTh">Client Org</th><th class="confluenceTh">Training Authority</th><th class="confluenceTh">% Complete</th><th class="confluenceTh">Status</th><th class="confluenceTh">Department</th></tr>';
		

		allTasks.sort(compareValues('status'));
		allTasks.forEach(function(task) {
			if(task.status == "Submitted"){
				tsubmittedTotal ++;				
			}else if(task.status == "Under Review"){
				tunderReviewTotal ++;				
			}else if(task.status == "In Progress"){
				tinProgressTotal ++;				
			}else if(task.status == "Completed"){
				tcompletedTotal ++;				
			}else if(task.status == "Cancelled"){
				tcancelledTotal ++;				
			}		


		var t = htmlToPlainText(task['description']);	
		t = t.replace(/;/g, "");		
		t = t.replace(/&/g, "");
		t = t.replace(/nbsp/g, " ");
		t = (t.length > 300) ? t.substring(0,300)+'...' : t;

		 var 	cleanDescription =  $("<div/>").html(t).text();
		 var cleanOrg = task['org'].replace(/&/g,"");
		 var cleanTa = task['ta'].replace(/&/g,"");
		 var cleanTitle = task['title'].replace(/&/g,"");
		 var cleanDO = task['department_owner'].replace(/&/g,"");


			tasksTable += 	'<tr><td colspan="1" class="confluenceTd">'+ '<a href="https://lp-pa.forces.gc.ca/portal/projects#/projects/view_task/'+ task['id'] + '">'+ cleanTitle  +'</a><br/><span>'+cleanDescription+'</span></td>';
			
			tasksTable += 	'<td colspan="1" class="confluenceTd">'+ cleanOrg  +'</td>';
			tasksTable += 	'<td colspan="1" class="confluenceTd">'+ cleanTa +'</td>';
			tasksTable += 	'<td colspan="1" class="confluenceTd">'+  task['percentage'] +'</td>';
			tasksTable += 	'<td colspan="1" class="confluenceTd">'+ task['status'] +'</td>';			
			tasksTable += 	'<td colspan="1" class="confluenceTd">'+ cleanDO  +'</td>';
			tasksTable += '</tr>';
		
		});

		tasksTable += 	'</tbody></table>';
 		taskPage += tasksTable;	

		//Unassigned

		var unassignedTable = '<table class="confluenceTable break" style="width:100%;"><colgroup><col style="width:60%;"></col><col></col><col></col><col></col><col></col><col></col></colgroup><tbody>';
		unassignedTable += 	'<tr><th class="confluenceTh"  style="width:60%;">Title</th><th class="confluenceTh">Client Org</th><th class="confluenceTh">Training Authority</th><th class="confluenceTh">% Complete</th><th class="confluenceTh">Status</th><th class="confluenceTh">Department</th></tr>';
		

		allUnassigned.sort(compareValues('status'));
		allUnassigned.forEach(function(un) {
			if(un.status == "Submitted"){
				usubmittedTotal ++;				
			}else if(un.status == "Under Review"){
				uunderReviewTotal ++;				
			}else if(un.status == "In Progress"){
				uinProgressTotal ++;				
			}else if(un.status == "Completed"){
				ucompletedTotal ++;				
			}else if(un.status == "Cancelled"){
				ucancelledTotal ++;				
			}		

		var u = htmlToPlainText(un['description']);	
		u = u.replace(/;/g, "");		
		u = u.replace(/&/g, "");
		u = u.replace(/nbsp/g, " ");
		u = (u.length > 300) ? u.substring(0,300)+'...' : u;

		 var 	cleanDescription =  $("<div/>").html(u).text();
		 var cleanOrg = un['org'].replace(/&/g,"");
		 var cleanTa = un['ta'].replace(/&/g,"");
		 var cleanTitle = un['title'].replace(/&/g,"");
		 var cleanDO = un['department_owner'].replace(/&/g,"");


			unassignedTable += 	'<tr><td colspan="1" class="confluenceTd">'+ '<a href="https://lp-pa.forces.gc.ca/portal/projects#/projects/view/'+ un['id'] + '">'+ cleanTitle  +'</a><br/><span>'+cleanDescription+'</span></td>';
			
			unassignedTable += 	'<td colspan="1" class="confluenceTd">'+ cleanOrg  +'</td>';
			unassignedTable += 	'<td colspan="1" class="confluenceTd">'+ cleanTa +'</td>';
			unassignedTable += 	'<td colspan="1" class="confluenceTd">'+  un['percentage'] +'</td>';
			unassignedTable += 	'<td colspan="1" class="confluenceTd">'+ un['status'] +'</td>';			
			unassignedTable += 	'<td colspan="1" class="confluenceTd">'+ cleanDO  +'</td>';
			unassignedTable += '</tr>';
		
		});

		unassignedTable += 	'</tbody></table>';
 		unassignedPage += unassignedTable ;	





		//Overview

		var pTotal = psubmittedTotal + punderReviewTotal + pinProgressTotal + pcompletedTotal + pcancelledTotal;
		var tTotal =   tsubmittedTotal + tunderReviewTotal + tinProgressTotal + tcompletedTotal + tcancelledTotal;
		var uTotal =   usubmittedTotal + uunderReviewTotal + uinProgressTotal + ucompletedTotal + ucancelledTotal;
		var submittedTotal =   psubmittedTotal + tsubmittedTotal + usubmittedTotal;		
		var underReviewTotal =   punderReviewTotal + tunderReviewTotal + uunderReviewTotal ;
		var inProgressTotal =   pinProgressTotal + tinProgressTotal + uinProgressTotal ;
		var completedTotal =   pcompletedTotal + tcompletedTotal + ucompletedTotal ;
		var cancelledTotal =   pcancelledTotal+ tcancelledTotal+ ucancelledTotal;



		var 	overviewTable = 	'<table class="confluenceTable break" style="width:100%;"><colgroup><col></col><col></col><col></col><col></col></colgroup><tbody>';
			overviewTable += 	'<tr><th class="confluenceTh">Status</th><th class="confluenceTh">Projects ('+pTotal+')</th><th class="confluenceTh">Tasks  ('+tTotal+')</th><th class="confluenceTh">Unassigned  ('+uTotal+')</th></tr>';


			if(vm.filters.status == '' || vm.filters.status == 'Submitted'){			
			overviewTable += '<tr><td colspan="1" class="confluenceTd">Submitted ('+submittedTotal +')</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+ psubmittedTotal+'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  tsubmittedTotal +'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  usubmittedTotal +'</td></tr>';
			}
			if(vm.filters.status == '' || vm.filters.status == 'Under Review'){	
			overviewTable += '<tr><td colspan="1" class="confluenceTd">Under Review ('+underReviewTotal +')</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+ punderReviewTotal+'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  tunderReviewTotal +'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  uunderReviewTotal +'</td></tr>';
			}
			if(vm.filters.status == '' || vm.filters.status == 'In Progress'){	 
			
			overviewTable += '<tr><td colspan="1" class="confluenceTd">In Progress ('+inProgressTotal +')</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+ pinProgressTotal+'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  tinProgressTotal +'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  uinProgressTotal +'</td></tr>';
			}
			if(vm.filters.status == '' || vm.filters.status == 'Completed'){	
			

			overviewTable += '<tr><td colspan="1" class="confluenceTd">Completed ('+completedTotal +')</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+ pcompletedTotal+'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  tcompletedTotal +'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  ucompletedTotal +'</td></tr>';
			}
			if(vm.filters.status == '' || vm.filters.status == 'Cancelled'){	


			overviewTable += '<tr><td colspan="1" class="confluenceTd">Cancelled ('+cancelledTotal +')</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+ pcancelledTotal+'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  tcancelledTotal +'</td>';
			overviewTable += '<td colspan="1" class="confluenceTd">'+  ucancelledTotal +'</td></tr>';
			}


			
		overviewTable += 	'</tbody></table>';
 		overviewPage += overviewTable ;	





		vm.overviewPage = overviewPage;
		vm.projectPage = projectPage;
		vm.taskPage = taskPage;
		vm.unassignedPage = unassignedPage;


	}
        /**
             * Initialization function
           
             */

        function init() {
            //create hashmap of projects, with department owners used as lookup id's 
            var projectsHash = {};
            vm.filterTabs = [{
                id: 'all',
                title: 'All'
            }];

            for (var i = 0; i < vm.projects.length; i++) {
                var project = vm.projects[i];

		var charMax = 300;
		project.cleanDescription =  (project.description.length > charMax) ? 
					$sce.trustAsHtml(htmlToPlainText(project.description).substring(0,charMax)+"...")
			 		: $sce.trustAsHtml(htmlToPlainText(project.description));
		project.description =  $sce.trustAsHtml(project.description);

                var projectOwner = projectOwnersLookup[project.department_owner];

                if (!projectsHash[projectOwner]) {
                    projectsHash[projectOwner] = [];

                    //create the filter tab
                    vm.filterTabs.push({
                        id: projectOwner,
                        title: elgg.echo(ownerPrefix + projectOwner)
                    });
                }
                projectsHash[projectOwner].push(project);
            }

            vm.allProjects = vm.projects;
            vm.projectsHash = projectsHash;


        }

        /**
         * Filter projects in the datatable using hash table lookup
         * 
         */
        vm.filterProjects = function(key) {
		vm.key = key;
            if (vm.key== 'all') {
                vm.projects = vm.allProjects;
                return;
            }
		console.log(vm.projectsHash[vm.key]);
		console.log(vm.key);


            vm.projects = vm.projectsHash[vm.key];
        }

        /**
         * Toggle the 'active' class on filter tab <li> eleements
         * 
         */
        vm.toggleFilterTab = function() {
		
		var t = "a#"+vm.key+".ng-binding";
		
		$(t).parent().siblings().removeClass('active');
		$(t).parent().addClass('active');
		$(t).addClass('active');
        }

        vm.selectProject = function(project) {
            vm.project = project;
        }

    }
})();
