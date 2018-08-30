(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('Projects', Projects);

		function Projects(project, user, notification, $location, Upload, $routeParams, $rootScope, helper, DTOptionsBuilder, $q) {
			var vm = this;
			
			vm.projects = [];
			vm.opis = [{}];
			
			//filter object
            vm.filters = {owner_guid:'', status:'', project_type:''};
            vm.filters.owner_guid = vm.filters.status = vm.filters.project_type = elgg.echo('projects:label:all');
            
			//JSON arrays for select dropdowns - this SHOULD be all retreived from a service or directive
			vm.statuses = [{name:elgg.echo('projects:label:submitted'), id: elgg.echo('projects:label:submitted')},{name:elgg.echo('projects:label:underreview'), id: elgg.echo('projects:label:underreview')},{name:elgg.echo('projects:label:inprogress'), id: elgg.echo('projects:label:inprogress')},{name:elgg.echo('projects:label:completed'),id:elgg.echo('projects:label:completed')}];
			vm.ta_options = {"values":[elgg.echo('projects:ta:air_force'),elgg.echo('projects:ta:army'),elgg.echo('projects:ta:mpc'),elgg.echo('projects:ta:navy')]};
			vm.projectTypes = {"values":[elgg.echo('projects:types:courseware'), elgg.echo('projects:types:enterprise_apps'),elgg.echo('projects:types:instructor_support'),elgg.echo('projects:types:learning_application'),elgg.echo('projects:types:learning_technologies'),
								elgg.echo('projects:types:mobile'),elgg.echo('projects:types:modelling'), elgg.echo('projects:types:rnd'), elgg.echo('projects:types:gaming'),elgg.echo('projects:types:support')]};
			vm.booleanOptions = {"values":[elgg.echo('projects:no'), elgg.echo('projects:yes')]};
			vm.multiOptions = {"values":[elgg.echo('projects:no'),elgg.echo('projects:update'),elgg.echo('projects:change')]};
			vm.department_options = {"values":[elgg.echo('projects:unassigned'),elgg.echo('projects:owner:learning_technologies'),elgg.echo('projects:owner:lsc'), elgg.echo('projects:owner:modernization'), elgg.echo('projects:owner:programmes')]};
			vm.classification_options = {"values":[elgg.echo('projects:unassigned'),elgg.echo('projects:project'),elgg.echo('projects:task')]};
			
			vm.ta = vm.ta_options.values[0];
			vm.type = vm.projectTypes.values[0];
			vm.isSme = vm.booleanOptions.values[0];
			vm.isLimitation = vm.booleanOptions.values[0];
			vm.updateExistingProduct = vm.multiOptions.values[0];
			vm.department_owner = vm.department_options.values[0];
			vm.classification = vm.classification_options.values[0];

			//make datatable default sorting by the fourth column(time-submitted)
			vm.dtOptions = DTOptionsBuilder.newOptions().withOption('order', [[3, 'desc']]);
			
			//get public key from the client
			var publicKey = localStorage.getItem('publicKey');
			
			//get single project
			if($routeParams.project_id) {
				$(window).scrollTop(0);
				vm.loaded = false;
				vm.sme = {};
				vm.usa = {};
				
				var paramObject = new Object();
				
				project.getProject(paramObject, $routeParams.project_id).then(function(results){
					vm.project = results;
                    
					//set default value for existing project from saved json data
					angular.forEach(vm.project, function(value, key){
						vm[key] = value;
					});
                    
					//create slider for percentage complete
					vm.slider = {
						'options': {
							start: function(event, ui) {
								$log.info('Event: Slider start - set with slider options', event);
							},
							stop: function(event, ui) {
								$log.info('Event: Slider stop - set with slider options', event);
							}
						}
					};
					
					vm.project.editable = [];
					vm.loaded = true;
				}, function(error){
					console.log(error);
				});
			}
			else{
				//get default (All) sorted list of projects
				var param = new Object();
				param.status = 'All';
				getProjectsByParam(param);
			}
			
			/*
			 * Helper Functions
			 */
			function getProjects(value) {
				//create the signature
				var paramObject = new Object();
				paramObject.status = value;
				
				//get the projects
				return $q(function(resolve, reject) {
					project.getProjects(publicKey, paramObject).then(function(results){
						vm.projects = results.data;
						resolve();
					}, function(error){
						reject(error);
					});
				});
			}
			
			/*
			 * scope functions
			 */
			
			//create a project
			vm.createProject = function (isValid) {
				if(isValid) {
					//display loading overlay
					$rootScope.isLoading = true;
					//prevent duplicate submission by disabling submit button after validation
					$('button[type="submit"]').attr('disabled', true);
					project.create({
						'classification': vm.classification,
						'comments':vm.comments,
						'course':vm.course,
						'department_owner': vm.department_owner,
						'description': vm.description,
						'is_limitation': vm.isLimitation,
						'is_sme_avail': vm.isSme,
						'life_expectancy': vm.lifeExpectancy,
						'opis': vm.opis,
						'org':vm.org,
						'percentage':0,
						'priority':vm.priority,
						'project_type':vm.type,
						'scope' : vm.scope,
						'sme' : vm.sme,
						'status': 'Submitted',
						'ta': vm.ta,
						'title':vm.title,
						'update_existing_product': vm.updateExistingProduct,
						'usa':vm.usa
					}).then(function(success) {
						//upload attachments
						Upload.upload({
							url: 'api/projects',
							data: {files:vm.files, 'projectId':success.data.id, 'accessId':success.data.accessId,'action':'attachFile'}
						}).then(function(success){
						
						}, function(error){
							console.log(error);
						});
						
						//notify project admins
						var filter = {'project_admin':true};
                        filter.project_type = vm.type;
                        
						user.getUsers(filter).then(function(result){
							var subject = 'New Support Request';
							var body = 'A new support request has been submitted by '+$rootScope.user.name+'. You can view the new support request at '+elgg.get_site_url()+'projects#/projects/view/'+success.data.id;
							angular.forEach(result.data, function(value, key){
								notification.create(subject, body, value.id).then(function(result){
									
								},function(error){
									console.log(error);
								});
							});
						}, function(error){
							console.log(error);
						});
						
						getProjects('Submitted').then(function(success){
							$rootScope.isLoading = false;
					
							$location.path('projects');
							$(window).scrollTop(0);
						}, function(error){
							console.log(error);
						});
						
					}, function(error){
						$rootScope.isLoading = false;
						$('button[type="submit"]').attr('disabled', false);
						//$location.path('projects');
						//$(window).scrollTop(0);
						console.log(error);
					});
				}
			}
			
			vm.deleteProject = function(id, index) {
				//display loading overlay
				$rootScope.isLoading = true;
				
				var paramObject = new Object();
				project.remove(paramObject, id).then(function(success){
					//Instead of reload all the projects, we just remove the corresponding project row from list
					//Cannot use 'delete vm.projects[index];', it will crash the datatables
					$('#statusSelect' + index).closest('tr').remove();
					//remove loading overlay
					$rootScope.isLoading = false;
				}, function(error){
					$rootScope.isLoading = false;
					console.log(error);
				});
			}
			
			vm.update = function(field) {
				project.update({
					'field':field,
					'value':vm[field]
				}, vm.project.id).then(function(success){
					vm.project[field] = vm[field];
				}, function(error){
					console.log(error);
				});
			}
	
			//partial update - status
			vm.updateStatus = function(index) {
				$('#statusSelect'+index).prop('disabled', 'disabled');
				project.update({
					'field':'status',
					'value':vm.projects[index].status
				}, vm.projects[index].id).then(function(success){
					$('#statusSelect'+index).prop('disabled', false);
				}, function(error){
					console.log(error);
				});
			}
			
			//helper methods
			vm.toggleContainer = function(toggle, container) {
				if(toggle=='Yes'){
					$('#'+container).show();
				}
				else if(toggle=='No'){
					$('#'+container).hide();
				}
			}

			//decide the boolean value of selected option box
			vm.boolOption = function(optionVal) {
				if (optionVal == 'Yes') {
					return true;
				}
				else {
					return false;
				}
			}
			
			//add opi to vm
			vm.addContact = function() {
				vm.opis.push({});
			}
			
			vm.removeContact = function(index) {
				vm.opis.splice(index, 1);
			}
            
            vm.filter = function(event) {
                var filter = $(event.target).attr('id');
                var filterType = $(event.target).attr('data-filter-type');
                
                //toggle menu item highlighting
                if(filterType == 'owner_guid'){
                    $("[id='"+filter+"'][data-filter-type="+filterType+"]").parent().siblings('.active').removeClass('active');
                    $("[id='"+filter+"'][data-filter-type="+filterType+"]").parent().addClass('active');
                    if(filter == 'mine'){
                        console.log($rootScope.user);
                        filter = $rootScope.user.id;
                    }
                }
                else{
                    $('.list-group-item.active[data-filter-type='+filterType+']').removeClass('active');
                    $("[id='"+filter+"'][data-filter-type="+filterType+"]").addClass('active');
                }
                
                //sort the projects
                vm.filters[filterType] = filter;
                getProjectsByParam(vm.filters).then(function(success){
                    
                }, function(error){
                    console.log(error);
                });
            }
            
			function getProjectsByParam(params) {
                //no need to add filter query param if set to All
                for(var key in params){
                    if (params.hasOwnProperty(key)) {
                        if(params[key] == 'All' || params[key] == 'all' ){
                            delete params[key];
                        }
                    }
                }

				//get the projects by params
				return $q(function(resolve, reject) {
					project.getProjects(publicKey, params).then(function(results){
						vm.projects = results.data;
						resolve();
					}, function(error){
						reject(error);
					});
				});
			}
			
			vm.toggleEditMode = function(event, i) {
				i = (typeof i === 'undefined') ? null : i;
				
				var value = event.target.attributes['data-id'].value;
				var element = $('.project').find("[data-field-id='"+event.target.attributes['data-id'].value+"']");
				
				if(element.hasClass('hidden')) {
					element.removeClass('hidden');
					$('a.edit-button.'+value).removeClass('hidden');
					
					//hide cancel and accept buttons
					if(value=='opi') {
						vm.project.editable[value] = {};
						vm.project.editable[value][i] = false;
					}
					else{
						vm.project.editable[value] = false;
					}
				}
				else{
					element.addClass('hidden');
					$('a.edit-button.'+value).addClass('hidden');
					
					//show cancel and accept buttons
					if(value=='opi') {
						vm.project.editable[value] = {};
						vm.project.editable[value][i] = true;
					}
					else{
						vm.project.editable[value] = true;
					}
				}
			}
			
			vm.toggleEditMode_variant = function(event) {
				var value = event.target.attributes['data-id'].value;
				vm.project.editable[value] ? vm.project.editable[value] = false : vm.project.editable[value] = true;
			}
			
			vm.animateToField = function(event) {
				var name = event.target.attributes['data-list-id'].value;
				var top = $("[data-row-id='"+name+"']").offset().top;
				$('html, body').animate({
					scrollTop: top
				}, 500);
			}
		}

})();