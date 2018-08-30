(function () {
    'use strict';

    angular
            .module('portal')
            .controller('Projects', Projects);

    function Projects(resolveProject, project, user, notification, $location, Upload, $routeParams, $rootScope, helper, DTOptionsBuilder, $q, $sce) {
        tinyMCE.remove();

        var vm = this;
        vm.projects = [];
        vm.project = resolveProject;
        vm.opis = [{}];
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

            //set default value for existing project from saved json data
            angular.forEach(vm.project, function (value, key) {
                vm[key] = value;
            });
            vm.project.description = $sce.trustAsHtml(vm.project.description);

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

            vm.project.editable = [];
            vm.loaded = true;
        } else {
            getProjects();

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
            tinyMCE.triggerSave();
            if (field == "description") {
                vm[field] = $('body').find('#description').val();
            } else if (field == "savings") {
                vm.savings.choices = vm.choices;
            }

            project.update({
                'field': field,
                'value': vm[field]
            }, vm.project.id).then(function (success) {
                if (field == 'description') {
                    vm.project[field] = $sce.trustAsHtml(vm[field]);
                } else {
                    vm.project[field] = vm[field];
                }
            }, function (error) {
                console.log(error);
            });
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
            
            //toggle menu item highlighting
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

})();
