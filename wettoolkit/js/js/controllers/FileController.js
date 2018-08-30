(function(){
	'use strict';
	
	angular
		.module('portal')
		.controller('FileCtrl', FileCtrl);
	
	function FileCtrl(FileService, EntityService, $rootScope) {
		var vm = this;
        vm.file = {
            file:{progress:0},
            title:'',
            description:'',
            container_guid: '',
            folder_id:'0',
            access_id:'1',
        };
        
        location.search.split('folder_guid=')[1] ? vm.file.folder_id = location.search.split('folder_guid=')[1] : '';
        
        EntityService.getEntity(elgg.get_page_owner_guid()).then(function(success){
            if(success.data.entity.is_group) {
                vm.file.access_id = success.data.entity.group_acl.toString();
                vm.file.container_guid = success.data.entity.id;
            }
        });
        
        vm.uploadFile = function(isValid){
            if(isValid) {
                //trigger loading screen
                vm.isLoading = true;
                vm.uploading = true;
                //type conversions
                vm.file.access_id = Number(vm.file.access_id);
                //get the categories and assign array to file object
                var cats = [];
                $("form[name='fileForm'] [name='categories[]']:checked").each(function(i){
                    cats[i] = $(this).val();
                });
                vm.file.categories = cats;
                
                FileService.create(vm.file).progress(function(prog){
                    vm.file.file.progress = Math.round((100 * prog.loaded / prog.total));
                    $('.progress-bar').css('width', vm.file.file.progress+'%');
                    if(vm.file.file.progress == 100) {
                        vm.uploading = false;
                        vm.saving = true;
                    }
                })
                .then(function(success){
                    window.location.href = '/portal/file/view/'+success.data.data.file.id;
                }, function(error){
                    console.log(JSON.stringify(error.data.data));
                    vm.isLoading = false;
                    vm.errorMessage = true;
                    vm.messages = error.data.data.message;
                });
            }
        }
	}
})();