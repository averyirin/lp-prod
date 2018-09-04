(function () {
    'use strict';

    angular
            .module('portal')
            .controller('Projects', Projects);

    function Projects(resolveProject, project, user, notification, $location, Upload, $routeParams, $rootScope, helper, DTOptionsBuilder, $q, $sce, $uibModal) {
        tinyMCE.remove();

        var vm = this;
        vm.projects = [];
        vm.project = resolveProject;
        vm.opis = [{}];

        $rootScope.chosenAddMethod = null;
        $rootScope.chosenSpace = null;
        vm.animationsEnabled = true;
	vm.selected = null;
        vm.classification_filter = {classification:elgg.echo('projects:project')}


        //filter object
        vm.filters = {owner_guid: '', status: '', project_type: '', department_owner: ''};
        vm.filters.owner_guid = vm.filters.status = vm.filters.project_type = vm.filters.department_owner = elgg.echo('projects:label:all');

        //JSON arrays for select dropdowns - this SHOULD be all retreived from a service or directive
        vm.statuses = [
            {name: elgg.echo('projects:label:submitted'), id: elgg.echo('projects:label:submitted')},
            {name: elgg.echo('projects:label:underreview'), id: elgg.echo('projects:label:underreview')},
            {name: elgg.echo('projects:label:inprogress'), id: elgg.echo('projects:label:inprogress')},
            {name: elgg.echo('projects:label:completed'), id: elgg.echo('projects:label:completed')}
        ];

        vm.ta_options = {
            "values": [
                elgg.echo('projects:pleaseselect'),
                elgg.echo('projects:ta:air_force'),
                elgg.echo('projects:ta:army'),
                elgg.echo('projects:ta:mpc'),
                elgg.echo('projects:ta:navy')
            ]
        };

        vm.projectTypes = {
            "values": [
                elgg.echo('projects:types:courseware'),
                elgg.echo('projects:types:enterprise_apps'),
                elgg.echo('projects:types:instructor_support'),
                elgg.echo('projects:types:learning_application'),
                elgg.echo('projects:types:learning_technologies'),
                elgg.echo('projects:types:mobile'),
                elgg.echo('projects:types:modelling'),
                elgg.echo('projects:types:rnd'),
                elgg.echo('projects:types:gaming'),
                elgg.echo('projects:types:support')
            ]
        };

        vm.booleanOptions = {
            "values": [
                elgg.echo('projects:no'),
                elgg.echo('projects:yes')
            ]
        };

        vm.multiOptions = {
            "values": [
                elgg.echo('projects:no'),
                elgg.echo('projects:update'),
                elgg.echo('projects:change')
            ]
        };

        vm.department_options = {
            "values": [
                //elgg.echo('projects:unassigned'),
                //elgg.echo('projects:owner:learning_technologies'),
                elgg.echo('projects:owner:lsc'),
                elgg.echo('projects:owner:alsc'),
                //elgg.echo('projects:owner:modernization'),
                //elgg.echo('projects:owner:programmes'),
                //elgg.echo('projects:owner:lt_lsc')
            ]
        };

        vm.classification_options = {
            "values": [
                elgg.echo('projects:unassigned'),
                elgg.echo('projects:project'),
                elgg.echo('projects:task')
            ]
        };

        //choices for the savings checkboxes
        vm.choices = {
            "projects:savings:productivityIncrease": {"title": elgg.echo('projects:savings:productivityIncrease')},
            "projects:savings:reduction": {"title": elgg.echo('projects:savings:reduction')},
            "projects:savings:rationalization": {"title": elgg.echo('projects:savings:rationalization')},
            "projects:savings:qualitative": {"title": elgg.echo('projects:savings:qualitative')}
        };

        //make datatable default sorting by the fourth column(time-submitted)
        vm.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [[3, 'desc']]);

        //get public key from the client
        var publicKey = localStorage.getItem('publicKey');

        //get single project
        if ($routeParams.project_id) {
            $(window).scrollTop(0);
            vm.loaded = false;
            vm.sme = {};
            vm.usa = {};
            vm.savings = {};

            //default on load
            vm.inConfluence = false;
            vm.bindConfluence = 'undefined';



            //set default value for existing project from saved json data
            angular.forEach(vm.project, function (value, key) {
                vm[key] = value;
            });
            vm.project.description = $sce.trustAsHtml(vm.project.description);
            vm.project.timeline = new Date(vm.project.timeline);



            //create slider for percentage complete
            vm.slider = {
                'options': {
                    start: function (event, ui) {
                        $log.info('Event: Slider start - set with slider options', event);
                    },
                    stop: function (event, ui) {
                        $log.info('Event: Slider stop - set with slider options', event);
                    }
                }
            };
	    getSpaceInConfluence(vm);
            vm.project.editable = [];
            vm.loaded = true;
        } else {

            var params = {};
            params.classification = elgg.echo('projects:project');
            //getProjects();
	    filterProjects('Project','classification'); 

            

            vm.project.ta = vm.ta_options.values[0];
            vm.project.project_type = vm.projectTypes.values[0];
            vm.project.is_sme_avail = vm.booleanOptions.values[0];
            vm.project.is_limitation = vm.booleanOptions.values[0];
            vm.project.update_existing_product = vm.multiOptions.values[0];
            vm.project.department_owner = vm.department_options.values[0];
            vm.project.classification = vm.classification_options.values[0];
        }

        /*
         * Helper Functions
         */

        function getProjects(params) {
            return $q(function (resolve, reject) {
                project.getProjects(params).then(function (results) {
                    vm.projects = results.data;
                    resolve();
                }, function (error) {
                    reject(error);
                });
            });
        }

	function filterProjects(id, dataFilterType){

            var filter = id;
            var filterType = dataFilterType;
            //Testing
            //console.log('filter = ' + filter);
            //console.log('type = ' + filterType);

            //toggle menu item highlighting
            if (filterType == 'classification') {
                $("[id='" + filter + "'][data-filter-type=" + filterType + "]").parent().siblings('.active').removeClass('active');
                $("[id='" + filter + "'][data-filter-type=" + filterType + "]").parent().addClass('active');
            }
            if (filterType == 'owner_guid') {
                $("[id='" + filter + "'][data-filter-type=" + filterType + "]").parent().siblings('.active').removeClass('active');
                $("[id='" + filter + "'][data-filter-type=" + filterType + "]").parent().addClass('active');
                if (filter == 'mine') {
                    filter = $rootScope.user.id;
                }
            } else {
                $('.list-group-item.active[data-filter-type=' + filterType + ']').removeClass('active');
                $("[id='" + filter + "'][data-filter-type=" + filterType + "]").addClass('active');
            }

            //sort the projects
            vm.filters[filterType] = filter;
            getProjectsByParam(vm.filters).then(function (success) {

            }, function (error) {
                console.log(error);
            });

	}




        function getProjectsByStatus(value) {
            var params = {};
            params.status = value;
            return getProjects(params);
        }

        function getProjectsByParam(params) {
            //no need to add filter query param if set to All
            for (var key in params) {
                if (params.hasOwnProperty(key)) {
                    if (params[key] == 'All' || params[key] == 'all' || params[key] == 'Toutes' || params[key] == 'toutes') {
                        delete params[key];
                    }
                }
            }
            return getProjects(params);
        }

        /*
         * scope functions
         */

        // Sets the department owner based on the unit selected in the add form
        vm.setDepartmentOwner = function (param) {
            if (param != elgg.echo('projects:pleaseselect')) {
                var deptOwner = null;

                switch(param) {
                    case elgg.echo('projects:ta:air_force'):
                        deptOwner = elgg.echo('projects:owner:alsc');
                        break;
                    case elgg.echo('projects:ta:army'):
                    case elgg.echo('projects:ta:navy'):
                    case elgg.echo('projects:ta:mpc'):
                        deptOwner = elgg.echo('projects:owner:lsc');
                        break;
                }

                vm.project.department_owner = deptOwner;
            }
        }

        //create a project
        vm.createProject = function (isValid) {
            tinymce.triggerSave();

            setTimeout(function () {
                //assign description attribute to the html generated by the mce editor
                vm.project.description = $('body').find('#description').val();
                if (isValid) {
                    //display loading overlay
                    $rootScope.isLoading = true;

                    if (!vm.project.hasOwnProperty('savings')) {
                        vm.project.savings = {};
                    }
                    vm.project.opis = vm.opis;
                    vm.project.savings.choices = vm.choices;
                    vm.project.percentage = 0;
                    vm.project.status = 'Submitted';
		    vm.project.classification = 'Project';

                    project.create(vm.project).then(function (success) {
                        //upload attachments
                        Upload.upload({
                            url: 'api/projects',
                            data: {files: vm.files, 'projectId': success.data.id, 'accessId': success.data.accessId, 'action': 'attachFile'}
                        }).then(function (success) {

                        }, function (error) {
                            console.log(error);
                        });

                        //notify project admins
                        var filter = {'project_admin': true};
                        filter.department_owner = vm.project.department_owner;

                        user.getUsers(filter).then(function (result) {
                            var subject = 'New Support Request';
                            var body = 'A new support request has been submitted by ' + $rootScope.user.name + '. You can view the new support request at ' + elgg.get_site_url() + 'projects#/projects/view/' + success.data.id;

                            angular.forEach(result.data, function (value, key) {
                                notification.create(subject, body, value.id).then(function (result) {

                                }, function (error) {
                                    console.log(error);
                                });
                            });
                        }, function (error) {
                            console.log(error);
                        });

                        getProjectsByStatus('Submitted').then(function (success) {
                            $rootScope.isLoading = false;

                            $location.path('projects');
                            $(window).scrollTop(0);
                        }, function (error) {
                            console.log(error);
                        });

                    }, function (error) {
                        $rootScope.isLoading = false;
                        console.log(error);
                    });
                }
            }, 500);
        }


vm.createTask = function (isValid) {

            tinymce.triggerSave();

            setTimeout(function () {
                //assign description attribute to the html generated by the mce editor
                vm.project.description = $('body').find('#description').val();
                vm.project.timeline = vm.project.timeline == null ? "" : new Date(vm.project.timeline);

                //vm.project.timeline = $('body').find('#timeline').val();
                if (isValid) {
                    //display loading overlay
                    $rootScope.isLoading = true;

                    if (!vm.project.hasOwnProperty('savings')) {
                        vm.project.savings = {};
                    }
                    vm.project.opis = vm.opis;
                    vm.project.savings.choices = vm.choices;
                    vm.project.percentage = 0;
                    vm.project.status = 'Submitted';
                    //Set the type to be Project
                    vm.project.classification = 'Task';
                    project.create(vm.project).then(function (success) {
                        //upload attachments
                        Upload.upload({
                            url: 'api/projects',
                            data: {files: vm.files, 'projectId': success.data.id, 'accessId': success.data.accessId, 'action': 'attachFile'}
                        }).then(function (success) {

                        }, function (error) {
                            console.log(error);
                        });

                        //notify project admins
                        var filter = {'project_admin': true};
                        filter.department_owner = vm.project.department_owner;

                        user.getUsers(filter).then(function (result) {
                            var subject = 'New Support Request';
                            var body = 'A new support request has been submitted by ' + $rootScope.user.name + '. You can view the new support request at ' + elgg.get_site_url() + 'projects#/projects/view/' + success.data.id;

                            angular.forEach(result.data, function (value, key) {
                                notification.create(subject, body, value.id).then(function (result) {

                                }, function (error) {
                                    console.log(error);
                                });
                            });
                        }, function (error) {
                            console.log(error);
                        });

                        getProjectsByStatus('Submitted').then(function (success) {
                            $rootScope.isLoading = false;

                            $location.path('projects');
                            $(window).scrollTop(0);
                        }, function (error) {
                            console.log(error);
                        });

                    }, function (error) {
                        $rootScope.isLoading = false;
                        console.log(error);
                    });
                }
            }, 500);
        }
        vm.deleteAttachment = function (id, key){
          Upload.upload({
              url: 'internapi/projects',
              data: {'attachmentIndex': key, 'projectId': id, 'action': 'detachFile'}
          }).then(function (success) {
              window.location.reload();
          }, function (error) {
              console.log(error);
          });
        }




        vm.deleteProject = function (id, index) {
            //display loading overlay
            $rootScope.isLoading = true;

            var paramObject = new Object();
            project.remove(paramObject, id).then(function (success) {
                //Instead of reload all the projects, we just remove the corresponding project row from list
                //Cannot use 'delete vm.projects[index];', it will crash the datatables
                $('#statusSelect' + index).closest('tr').remove();
                //remove loading overlay
                $rootScope.isLoading = false;
            }, function (error) {
                $rootScope.isLoading = false;
                console.log(error);
            });
        }

        vm.update = function (field) {
		if(field == 'timeline' && vm.project.classification == "Task"){
            		vm[field] =  new Date(vm.project[field]);
          	}

            tinyMCE.triggerSave();

if (field == 'attachments'){
            //upload attachments
            Upload.upload({
                url: 'api/projects',
                data: {files: vm.files, 'projectId': vm.project.id, 'accessId': vm.project.access_id, 'action': 'attachFile'}
            }).then(function (field) {		
		window.location.reload();		
            }, function (error) {
                console.log(error);
            });
		
          }else{








            if (field == "description") {
                vm[field] = $('body').find('#description').val();
            } else if (field == "savings") {
                vm.savings.choices = vm.choices;
            }
            project.update({
                'field': field,
                'value': vm[field]
            }, vm.project.id).then(function (success) {
		if(field == 'classification'){
                  if(vm[field] == 'Task'){
                    $location.path('projects/view_task/'+vm.project.id);
                  }else{
                    $location.path('projects/view/'+vm.project.id);
                  }
                }else if (field == 'description') {
                    vm.project[field] = $sce.trustAsHtml(vm[field]);
                } else {
                    vm.project[field] = vm[field];
                }
            }, function (error) {
                console.log(error);
            });
        	}
	}

        //partial update - status
        vm.updateStatus = function (index) {
            $('#statusSelect' + index).prop('disabled', 'disabled');

            project.update({
                'field': 'status',
                'value': vm.projects[index].status
            }, vm.projects[index].id).then(function (success) {
                $('#statusSelect' + index).prop('disabled', false);
            }, function (error) {
                console.log(error);
            });
        }

        //decide the boolean value of selected option box
        vm.boolOption = function (optionVal) {
            if (optionVal == 'Yes') {
                return true;
            } else {
                return false;
            }
        }

        vm.emptyObject = function (object, exclusion) {
            for (var prop in object) {
                if (object.hasOwnProperty(prop)) {
                    if (prop != exclusion) {
                        delete object[prop];
                    }
                }
            }
        }

        //add opi to stack
        vm.addContact = function () {
            vm.opis.push({});
        }

        //remove opi from stack
        vm.removeContact = function (index) {
            vm.opis.splice(index, 1);
        }

        vm.filter = function (event) {
  	    var filter = $(event.target).attr('id');
            var filterType = $(event.target).attr('data-filter-type');
	    filterProjects(filter, filterType);     
        }

        vm.toggleEditMode = function (event, i) {
            i = (typeof i === 'undefined') ? null : i;

            var value = event.target.attributes['data-id'].value;
            var element = $('.project').find("[data-field-id='" + event.target.attributes['data-id'].value + "']");

            if (element.hasClass('hidden')) {
                element.removeClass('hidden');
                $('a.edit-button.' + value).removeClass('hidden');

                //hide cancel and accept buttons
                if (value == 'opi') {
                    vm.project.editable[value] = {};
                    vm.project.editable[value][i] = false;
                } else {
                    vm.project.editable[value] = false;
                }
            } else {
                element.addClass('hidden');
                $('a.edit-button.' + value).addClass('hidden');

                //show cancel and accept buttons
                if (value == 'opi') {
                    vm.project.editable[value] = {};
                    vm.project.editable[value][i] = true;
                } else {
                    vm.project.editable[value] = true;
                }
            }
        }

        vm.toggleEditMode_variant = function (event) {
            var value = event.target.attributes['data-id'].value;
            vm.project.editable[value] ? vm.project.editable[value] = false : vm.project.editable[value] = true;
        }

        vm.animateToField = function (event) {
            var name = event.target.attributes['data-list-id'].value;
            var top = $("[data-row-id='" + name + "']").offset().top;
            $('html, body').animate({
                scrollTop: top
            }, 500);
        }

        vm.printAll = function (opMandate) {
            var projects = vm.projects.filter(function (el) {
                return (el.op_mandate === opMandate);
            });
            var printContents = '<h1>DRT 5.1 Projects</h1>';

            for (var i = 0; i < projects.length; i++) {
                var project = projects[i];

                try {
                    project.opis = JSON.parse(project.opis);
                    project.sme = JSON.parse(project.sme);
                    project.usa = JSON.parse(project.usa);
                    project.savings = JSON.parse(project.savings);
                } catch (e) {
                    console.log(e);
                }

                printContents += "<h2 style='margin-top: 2.25rem; padding-bottom:1rem; border-bottom: 1px solid grey; font-weight:bold;'>Project #" + (parseInt(i) + 1) + "</h2>";
                printContents += getPrintContent(project);
            }

            var popupWin = window.open('', '_blank', 'scrollbars=1,resizable=1,width=1024,height=778');
            popupWin.document.open();
            popupWin.document.write('<html><head><style>body{font-family:sans-serif; margin: 2.25rem;} p{margin:.667rem 0 2.25rem; white-space: pre-wrap;}</style></head><body onload="window.print()">' + printContents + '</body></html>');
            popupWin.document.close();
        }




//return if the space exisits in confluence
        function getSpaceInConfluence(vm){
            project.getSpace(vm.project.id).then(function (result) {
               vm.inConfluence = result.data;
               if(vm.inConfluence){
                   vm.confluenceUrl = result.url;
               }
            }, function (error) {
                console.log(error);
            });
        }
 //get all the spaces without Project Charter
        vm.getSpaces = function (size) {
            //display loading overlay
            $rootScope.isLoading = true;
            project.getSpaces().then(function (success) {
                var allSpacesArr = [];
                var keyArr = [];
                for (var index in success.results) {
                    var space = new Object();
                    space.key = success.results[index]['key'];
                    space.name= success.results[index]['name'];
                    allSpacesArr.push(space);
                    keyArr.push(space.key);
                }
                $rootScope.allSpaces = allSpacesArr;
                $rootScope.newSpaceKey = getCondensedKey(vm.title, keyArr);
                //remove loading overlay
                $rootScope.isLoading = false;
                $rootScope.isModalOpen = true;

                //show Confluence Integration modal
                var modalInstance = $uibModal.open({
                    animation: vm.animationsEnabled,
                    windowClass: "confluenceModal",
                    backdropClass: "full-screen loading-screen",
                    templateUrl: 'confluenceModal.html',
                    controller: 'ModalInstanceCtrl',
                    size: size,
                    scope: $rootScope
                });
                //get results from confluence modal
                modalInstance.result.then(function (result) {
                    vm.chosenAddMethod = result[0];
                    var spaceKey = null;
                    //Set the space key depending on exisitng or new (autogenerated) space
                    if(vm.chosenAddMethod == 'old'){
                        spaceKey = vm.chosenSpace = result[1];
                    }else{
                        spaceKey = $rootScope.newSpaceKey;
                    }
                    //create the new project charter
                    vm.createNewProjectCharter(vm.chosenAddMethod, spaceKey);
                    //close the modal
                    $rootScope.isModalOpen = false;
                }, function () {
                    $rootScope.isModalOpen = false;
                });
            }, function (error) {
                $rootScope.isLoading = false;
                $rootScope.errorMessage = true;
                $rootScope.message =  error.data.data;
            });
        }

        //create a project
        vm.createNewProjectCharter = function (addMethod, spaceKey) {
            //Get the formatted HTML Project Charter information
            var projectCharter = generateProjectCharter();

            //display loading overlay
            $rootScope.isLoading = true;
            //create the charter in confluence
            project.createNewCharter(projectCharter, addMethod, spaceKey, vm.project.id, vm.project.title).then(function (result) {
                //sets the btn to be "view in confluence"
                getSpaceInConfluence(vm);
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

        //formatted html of the project charter
        function generateProjectCharter() {


            //Table
            var projCharterPg1 = "<div class='projCharter'><table class='break' style='width:100%;'>";
            //Title and Date
            projCharterPg1 += "<tr><th colspan='2'><strong>MPC LSC PROJECT CHARTER</strong></th></tr>";
            projCharterPg1 += "<tr><td><strong>" + "Learning Support Request Project Title and Date" + "</strong></td>" +
                "<td><strong>" + ((vm.project.title == null) ? "<p> </p>" : vm.project.title) + "<br />" +
                ((vm.project.time_created == null) ? "<p> </p>" : vm.project.time_created) + "</strong></td></tr>";
            //Completed By
            projCharterPg1 += "<tr><td><strong>" + "For Completion By:" + "</strong></td>" +
                "<td></td></tr>";

            //Associated Course
            projCharterPg1 += "<tr><td><strong>" + "Associated Course:" + "</strong></td>" +
                "<td><strong>" + ((vm.project.course == null) ? "No" : vm.project.course) + "</strong></td></tr>";

            //Client Organization
            projCharterPg1 += "<tr><td><strong>" + "Client Organization:" + "</strong></td>" +
                "<td><strong>" + ((vm.project.org == null) ? "<p> </p>" : vm.project.org) + "</strong></td></tr>";

            //Training Authority
            projCharterPg1 += "<tr><td><strong>" + "Training Authority:" + "</strong></td>" +
                "<td><strong>" + ((vm.project.ta == null) ? "<p> </p>" : vm.project.ta) + "</strong></td></tr>";

            //Department Owner
            projCharterPg1 += "<tr><td><strong>" + "Department Owner:" + "</strong></td>" +
                "<td><strong>" + ((vm.project.department_owner == null) ? "<p> </p>" : vm.project.department_owner) + "</strong></td></tr>";

            //Project Type
           /* projCharterPg1 += "<tr><td><strong>" + "Type:" + "</strong></td>" +
                "<td><strong>" + ((vm.project.project_type == null) ? "<p> </p>" : vm.project.project_type) + "</strong></td></tr>";
            */
            //Attached files
            var attachedFiles = '';
            if (vm.project.attachments != null) {
                for (var index in vm.project.attachments) {
		var baseUrl = elgg.get_site_url();
		baseUrl = (baseUrl.slice(-1) == "/") ? baseUrl : baseUrl+"/";

                    var attachment = vm.project.attachments[index];
                    //<a href='{{attachment.url}}'>{{attachment.title}}</a>

                    attachedFiles += "<a href='" + baseUrl + attachment.url + "'>" + attachment.title + "</a><br />";
                }
            }

            //Description of project
            projCharterPg1 += "<tr><td colspan='2'><strong>" + "Description:" + "</strong><br />" +
                ((vm.description == null) ? "<p> </p>" : vm.description) + "<br />" +
                ((vm.project.comments == null) ? "<p> </p>" : vm.project.comments) + "<br />" +
                ((attachedFiles == '') ? "<p> </p>" : attachedFiles) + "<br />" +
                "<strong>" + "Goals:" + "</strong><br />" + "Insert goals here" + "</td></tr>";


            //Known history
            projCharterPg1 += "<tr><td colspan='2'><strong>" + "(Known) History:" + "</strong><br />" +
                "Insert known history here" + "</td></tr>";

            //Organizational priorities
            projCharterPg1 += "<tr><td colspan='2'><strong>" + "Organizational Priorities/Operational Mandate" + "</strong><br />" +
                ((vm.project.priority == null) ? "<p> </p>" : vm.project.priority) + "</td></tr>";

            //Impact
            projCharterPg1 += "<tr><td colspan='2'><strong>" + "Impact" + "</strong><br />" +
                ((vm.project.impact == null) ? "<p> </p>" : vm.project.impact) + "</td></tr>";

            //Limitations
            projCharterPg1 += "<tr><td colspan='2'><strong>" + elgg.echo('projects:is_limitation') + "</strong><br />" +
                ((vm.project.is_limitation == null) ? "<p> </p>" : vm.project.is_limitation) + "</td></tr>";

            //Is an update to exisiting product
            projCharterPg1 += "<tr><td colspan='2'><strong>" + elgg.echo('projects:updateExistingProduct') + "</strong><br />" +
                ((vm.project.update_existing_product == null) ? "<p> </p>" : vm.project.update_existing_product) + "</td></tr>";

            //End table
            projCharterPg1 += "</table>";

            //Table
            var projCharterPg2 = "<table class='break'  style='width:100%;'>";
            //Life expectancy
            projCharterPg2 += "<tr><td colspan='2'><strong>" + "What is the product/course life expectancy?" + "</strong></td>" +
                "<td style='width:60%;'>" + ((vm.project.life_expectancy == null) ? "<p> </p>" : vm.project.life_expectancy) + "</td></tr>";

            //Investments required
            projCharterPg2 += "<tr><td colspan='2'><strong>" + "List any investments that are required." + "</strong></td>" +
                "<td>" + ((vm.project.investment == null) ? "<p> </p>" : vm.project.investment) + "</td></tr>";

            //Potential savings
            var savings = '';
            if (vm.project.savings != null) {

                for (var index in vm.project.savings.choices) {
                    var choice = vm.project.savings.choices[index];
                    if (choice.selected) {
                        savings += choice.title + "<br />";
                    }
                }
                savings += ((vm.project.savings.substantiation == null) ? "<p> </p>" : vm.project.savings.substantiation);

            }
            projCharterPg2 += "<tr><td colspan='2'><strong>" + "Identify potential savings (return on investment and cost avoidance)." + "</strong></td>" +
                "<td>" + savings + "</td></tr>";

            //Team resources required
            projCharterPg2 += "<tr><td rowspan='6' style='width:10%;' ><strong>" + "Team Resources" + "</strong></td>";

            //MPC LSC OPI / Manager
            projCharterPg2 += "<td style='width:30%;'>" + "MPC LSC OPI/Manager" + "</td>" +
                "<td style='width:60%;'></td></tr>";

            //Client OPIs
            var clientOpis = '';
            for (var index in vm.project.opis) {
                var client = vm.project.opis[index];
                clientOpis += client.name + ", " + client.rank + "<br />";
                clientOpis += "<a href='" + "mailto:" + client.email + "'>" + client.email + "</a><br />";
                clientOpis += client.phone + "<br />";
            }
            projCharterPg2 += "<tr><td>" + "Client OPI" + "</td>" +
                "<td>" + clientOpis + "</td></tr>";

            //Instructional Designer
            projCharterPg2 += "<tr><td>" + "Instructional Designer" + "</td>" +
                "<td></td></tr>";

            //Developer
            projCharterPg2 += "<tr><td>" + "Developer" + "</td>" +
                "<td></td></tr>";

            //Available SMEs
            var availSmes = '';
            if (vm.project.is_sme_avail != "No") {
                var sme = vm.project.sme;
                if (sme != null) {
                    availSmes += sme.name + ", " + sme.rank + "<br />";
                    availSmes += "<a href='" + "mailto:" + sme.email + "'>" + sme.email + "</a><br />";
                    availSmes += sme.phone + "<br />";
                }
            }
            projCharterPg2 += "<tr><td>" + "Available SMEs:" + "</td>" +
                "<td>" + availSmes + "</td></tr>";

            //Unit signing authorities
            var usaList = '';
            var usa = vm.project.usa;
            usaList += ((usa.rank == null) ? undefined : usa.rank) + " " + usa.name + ", " + usa.position + "<br />";
            usaList += "<a href='" + "mailto:" + usa.email + "'>" + usa.email + "</a><br />";

            projCharterPg2 += "<tr><td>" + "Unit Signing Authorit(ies)" + "</td>" +
                "<td>" + usaList + "</td></tr>";

            //Objectives
            projCharterPg2 += "<tr><th colspan='3'><strong>" + "OBJECTIVES" + "</strong></th></tr>";
            projCharterPg2 += "<tr><td colspan='3'>" + "The objectives of this project are to:" +
                "<ul><li>First objective</li><li>Second objective</li><li>Third objective</li></ul>" + "</td></tr>";

            //end table
            projCharterPg2 += "</table>";

            //Table
            var projCharterPg3 = "<table class='break'  style='width:100%;'>";
            //Scope of work
            projCharterPg3 += "<tr><th colspan='2'><strong>" + "SCOPE" + "</strong></th></tr>";
            projCharterPg3 += "<tr><td colspan='2'><p>" + ((vm.project.scope == null) ? "<p>&nbsp;</p>" : vm.project.scope) + "</p></td></tr>";
            projCharterPg3 += "<tr><td colspan='2'><strong>" + "The project scope does NOT include the following:" +
                "</strong><ul><li>First not in scope</li><li>Second  not in scope</li><li>Third  not in scope</li></ul>" + "</td></tr>";

            //Milestones and Deliverables
            projCharterPg3 += "<tr><th style='width:50%;text-align: center'><strong>Milestones</strong></th><th style='width:50%;text-align: center'><strong>Expected Date</strong></th></tr>";
            projCharterPg3 += "<tr><td>Training</td><td></td></tr>";
            projCharterPg3 += "<tr><td>Design of Project</td><td></td></tr>";
            projCharterPg3 += "<tr><td>Alpha version</td><td></td></tr>";
            projCharterPg3 += "<tr><td>Beta version</td><td></td></tr>";
            projCharterPg3 += "<tr><td>Pilot version</td><td></td></tr>";
            projCharterPg3 += "<tr><th style='text-align: center'><strong>Deliverables</strong></th><th style='text-align: center'><strong>Estimated Completion Timeframe</strong></th></tr>";
            projCharterPg3 += "<tr><td>" + ((vm.project.timeline == null) ? "<p>Deliverable 1</p>" : vm.project.timeline) + "</td><td></td></tr>";
            projCharterPg3 += "<tr><td>" + "Deliverable 2" + "</td><td></td></tr>";
            projCharterPg3 += "<tr><td>" + "Deliverable 3" + "</td><td></td></tr>";

            //End table
            projCharterPg3 += "</table>";

            //Table
            var projCharterPg4 = "<table class='break'  style='width:100%;'>";

            //Assumptions
            projCharterPg4 += "<tr><th colspan='3'><strong>ASSUMPTIONS</strong></th></tr>";
            projCharterPg4 += "<tr><td colspan='3'>" + "The following assumptions have been made in documenting this charter:" +
                "<ul><li>First assumption</li><li>Second  assumption</li><li>Third assumption</li></ul>" + "</td></tr>";

            //Risk
            projCharterPg4 += "<tr><th colspan='3'><strong>RISKS</strong></th></tr>";
            projCharterPg4 += "<tr><td colspan='3'>" + ((vm.project.risk == null) ? "<p>&nbsp;</p>" : vm.project.risk) + "</td></tr>";

            //Sign off
            projCharterPg4 += "<tr><th style='width:30%;'><strong>NAME</strong></th>" +
                "<th style='width:35%;'><strong>SIGNATURE</strong></th>" +
                "<th style='width:35%;'><strong>DATE</strong></th></tr>";
            projCharterPg4 += "<tr><td><strong>MPC LSC Signing Authority:</strong><br /><br /></td>" +
                "<td></td>" +
                "<td></td></tr>";
            projCharterPg4 += "<tr><td><strong>Project Signing Authority:</strong><br /><br /></td>" +
                "<td></td>" +
                "<td></td></tr>";
            //end table and document
            projCharterPg4 += "</table></div>";

            //some styling
            var projCharterStyle = '<style>.projCharter table,.projCharter table td,.projCharter table th{border:1px solid #000}.projCharter .break{page-break-before:always}.projCharter td{vertical-align: top;}.projCharter table{border-collapse:collapse;text-align:left;width:100%;table-layout: fixed}.projCharter table th{background-color:#EEE}.projCharter table tr:first-child th{border-top:0}.projCharter table tr:last-child td{border-bottom:0;}.projCharter table tr td:first-child,.projCharter table tr th:first-child{border-left:0}.projCharter table tr td:last-child,.projCharter table tr th:last-child{border-right:0}}</style>';

            //set the document to be all 4 tables
            var projCharterDocument = projCharterPg1 + projCharterPg2 + projCharterPg3 + projCharterPg4;
            return projCharterDocument;
        }

        //show the Confluence Modal
        vm.addConfluence = function (size) {
            //display loading overlay
            vm.getSpaces(size);

        };


        vm.toggleAnimation = function () {
            vm.animationsEnabled = !vm.animationsEnabled;
        };





        function getPrintContent(project) {
            var fields = ['title', 'department_owner', 'status', 'course', 'org', 'ta', 'project_type', 'description', 'scope', 'opis',
                'op_mandate', 'priority', 'is_limitation', 'update_existing_product', 'life_expectancy', 'usa',
                'comments', 'investment', 'risk', 'timeline', 'impact', 'savings'];
            var printContents = '';

            for (var i = 0; i < fields.length; i++) {
                var object = project[fields[i]];
                var value = '';
                if (object) {
                    if (fields[i] == 'opis') {
                        for (var index in project[fields[i]]) {
                            value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:opi:title') + " " + (parseInt(index) + 1) + "</h4>";
                            value += "<label style='font-weight:bold;'>" + elgg.echo('projects:rank') + ":</label> " + object[index].rank + "<br/>";
                            value += "<label style='font-weight:bold;'>" + elgg.echo('projects:name') + ":</label> " + object[index].name + "<br/>";
                            value += "<label style='font-weight:bold;'>" + elgg.echo('projects:phone') + ":</label> " + object[index].phone + "<br/>";
                            value += "<label style='font-weight:bold;'>" + elgg.echo('projects:email') + ":</label> " + object[index].email + "<br/>";
                        }
                    } else if (fields[i] == 'usa') {
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:rank') + ":</label> " + object.rank + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:name') + ":</label> " + object.name + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:position') + ":</label> " + object.position + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:email') + ":</label> " + object.email + "<br/>";
                    } else if (fields[i] == 'savings') {
                        value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:savings:label') + "</h4>";

                        for (var index in object.choices) {
                            var choice = object.choices[index];
                            if (choice.selected) {
                                value += "<p>" + choice.title + "</p>";
                            }
                        }

                        value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:savings:substantiation') + "</h4>";
                        value += "<p>" + object.substantiation + "</p>";
                    } else {
                        object ? value = object : value = '&nbsp;';
                    }
                }

                printContents += '<div>';
                printContents += '<label style="font-size:1.5rem; font-weight:bold;">' + elgg.echo('projects:' + fields[i]) + '</label>';
                printContents += '<p>' + value + '</p>';
                printContents += "</div>";
            }

            return printContents;
        }

        vm.print = function () {
            var fields = ['title', 'department_owner', 'status', 'course', 'org', 'ta', 'project_type', 'description', 'scope', 'opis',
                'op_mandate', 'priority', 'is_limitation', 'update_existing_product', 'life_expectancy', 'usa',
                'comments', 'investment', 'risk', 'timeline', 'impact', 'savings'];
            var printContents = '<h1>DRT 5.1 Project</h1>';

            for (var i = 0; i < fields.length; i++) {
                var object = vm.project[fields[i]];
                var value = '';

                if (fields[i] == 'opis') {
                    for (var index in vm.project[fields[i]]) {
                        value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:opi:title') + " " + (parseInt(index) + 1) + "</h4>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:rank') + ":</label> " + object[index].rank + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:name') + ":</label> " + object[index].name + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:phone') + ":</label> " + object[index].phone + "<br/>";
                        value += "<label style='font-weight:bold;'>" + elgg.echo('projects:email') + ":</label> " + object[index].email + "<br/>";
                    }
                } else if (fields[i] == 'usa') {
                    value += "<label style='font-weight:bold;'>" + elgg.echo('projects:rank') + ":</label> " + object.rank + "<br/>";
                    value += "<label style='font-weight:bold;'>" + elgg.echo('projects:name') + ":</label> " + object.name + "<br/>";
                    value += "<label style='font-weight:bold;'>" + elgg.echo('projects:position') + ":</label> " + object.position + "<br/>";
                    value += "<label style='font-weight:bold;'>" + elgg.echo('projects:email') + ":</label> " + object.email + "<br/>";
                } else if (fields[i] == 'savings') {
                    value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:savings:label') + "</h4>";

                    for (var index in object.choices) {
                        var choice = object.choices[index];
                        if (choice.selected) {
                            value += "<p>" + choice.title + "</p>";
                        }
                    }

                    value += "<h4 style='font-weight:bold;'>" + elgg.echo('projects:savings:substantiation') + "</h4>";
                    value += "<p>" + object.substantiation + "</p>";
                } else {
                    object ? value = object : value = '&nbsp;';
                }

                printContents += '<div>';
                printContents += '<label style="font-size:1.5rem; font-weight:bold;">' + elgg.echo('projects:' + fields[i]) + '</label>';
                printContents += '<p>' + value + '</p>';
                printContents += "</div>";
            }

            var popupWin = window.open('', '_blank', 'scrollbars=1,resizable=1,width=1024,height=778');
            popupWin.document.open();
            popupWin.document.write('<html><head><style>body{font-family:sans-serif; margin: 2.25rem;} p{margin:.667rem 0 2.25rem; white-space: pre-wrap;}</style></head><body onload="window.print()">' + printContents + '</body></html>');
            popupWin.document.close();
        }
    }
 function contains(a, obj) {
            for (var i = 0; i < a.length; i++) {
                if (a[i] === obj) {
                    return true;
                }
            }
            return false;
        }
        //Autogenerates a unique key for Confluence Space
        function findKey(titleToKeyArr, notInKeyList, offset) {

            offset = (typeof offset !== 'undefined') ? offset : 0;
            var key = "";
            for (var word in titleToKeyArr) {
                key += titleToKeyArr[word].substr(0, (1 + offset)).toUpperCase();
            }
            var hasKey = contains(notInKeyList, key);
            return {"hasKey": hasKey, "key": key};

        }
        //Confluence unique key builder
        function getCondensedKey(title, notInKeyList) {
            var returnKey = "";
            var hasKey = true;
            var offset = 0;
            var regExpStr = /[a-zA-Z0-9]+[a-zA-Z0-9]*/g;
            var titleToKeyArr = title.match(regExpStr);
            while(hasKey == true){
                var potentialKey = findKey(titleToKeyArr, notInKeyList, offset);
                hasKey = potentialKey["hasKey"];
                returnKey = potentialKey["key"];
                offset++;
            }
            return returnKey;
        }
        //Confluence Modal controller
        angular.module('portal').controller('ModalInstanceCtrl', function ($scope, $uibModalInstance) {
            $scope.spaceType = 'new';
            $scope.selectSpace = $scope.allSpaces[0]['key'];
            $scope.ok = function () {
                var result = [$scope.spaceType, $scope.selectSpace];
                $uibModalInstance.close(result);
            };

            $scope.cancel = function () {
                $uibModalInstance.dismiss('cancel');
            };
        });


})();
