<?php
	set_time_limit(300);
	$access_level = elgg_get_ignore_access();
	elgg_set_ignore_access();

	$guid = get_input('group_guid');
	$groupName = get_input('name');

	
	function get_page_children(ElggObject $page, ElggObject $newCreatedPage, ElggObject $oldPage, $newGroup, $depth = 0, $pages = false, $oldParentGuid = false, $newParentGuid=false, $isNested = false,
			$parentGuidArr = array()){
		
		$options = array(
				"type" => "object",
				"subtype" => "page",
				"limit" => false,
				"metadata_name_value_pairs" => array("parent_guid" => $page->getGUID())
		);

		$resultEntity = array();
		if($children = elgg_get_entities_from_metadata($options)){
				$result = array();
				
				foreach($children as $child){
					$order = $child->order;
					if($order === NULL){
						$order = $child->time_created;
					}
					
					while(array_key_exists($order, $resultEntity)){
						$order++;
					}
					
					$resultEntity[$order] = $child;
				}
				
				ksort($resultEntity);
		}
					

		foreach($resultEntity as $order => $child){
			//Create new group subgroup
			//create group pages
				$newGroupPage = clone $child;
				$newGroupPage->container_guid = $newGroup->guid;
				$newGroupPage->access_id = $child->access_id;

				$date = new DateTime();
				$newTimeStamp = $date->getTimestamp();
			
				$newGroupPage->time_created = $newTimeStamp;
				$newGroupPage->time_updated = $newTimeStamp;
				$newGroupPage->last_action = $newTimeStamp;


				if ($newGroupPage->save()) {

				//check in the page becaused the user just saved it
				if($newGroupPage->deleteMetadata("checkedOut")){
					//system_message(elgg_echo('pages:checked_in'));
				}
				else{
					//system_message('Page could not be checked in. It is still locked for editing');
				}
				elgg_clear_sticky_form('page');
				// Now save description as an annotation
				$newGroupPage->annotate('page', $newGroupPage->description, $newGroupPage->access_id);
				//system_message(elgg_echo('pages:saved'));	
				add_to_river('river/object/page/create', 'create', elgg_get_logged_in_user_guid(), $newGroupPage->guid);
				}	

				
				//add tags to sub page
				//if page is tagged in a category, need to update to reflect copied groups category
				if($newGroupPage->universal_categories){
					$categories = elgg_get_entities(array(
						'type' => 'object',
						'subtype' => 'hjcategory',
						'order_by' => 'e.last_action desc',
						'limit' => 40,
						'full_view' => false,
						'container_guid' => $newGroup->guid
					));

					$copyCategories = elgg_get_entities_from_relationship(array(
						'relationship' => HYPECATEGORIES_RELATIONSHIP,
						'relationship_guid' => $child->guid,
						'inverse_relationship' => false,
						'limit' => 150
					));

					foreach($categories as $category){
						foreach($copyCategories as $c){
							if($category->title == $c->title){
								add_entity_relationship($newGroupPage->guid, HYPECATEGORIES_RELATIONSHIP, $category->guid);
							}
						}
					}
				}

						
			if($child->parent_guid != $oldParentGuid){
				$oldParentGuid = $newCreatedPage->guid;
			}


				//add parent_guid and order metadata
				$newGroupPage->parent_guid = $newCreatedPage->guid;
				//$previousParentGuid = $newCreatedPage->guid;
				$nestedGuid = $newCreatedPage->guid;
				$newGroupPage->order = $child->order;
				$newCreatedPage = $newGroupPage;
			
			if($isNested){			
				if(!$parentGuidArr[$depth]){$parentGuidArr[$depth] = $oldParentGuid;}
			}else{

				if(!$parentGuidArr[$depth]){$parentGuidArr[$depth] = $oldPage->guid;}
			}	
			$newGroupPage->parent_guid = $parentGuidArr[$depth];	

				//create group pages
				get_page_children($child, $newCreatedPage, $newGroup,$oldPage, $depth + 1, $pages, $oldParentGuid, $nestedGuid, true, $parentGuidArr);
		}	

		return $pages;
	}


	function copyGroup($guid, $name, $parentGroupGuid = null, array $options = null)
	{
		$inheritMembers = $_POST['inheritMembers'];
		$inheritFiles = $_POST['inheritFiles'];
		$inheritForums = $_POST['inheritForums'];		
		$inheritPages = $_POST['inheritPages'];
		$inheritSubGroups = $_POST['subGroups'];
		if($options) {
			$inheritMembers = $options['inheritMembers'];
			$inheritFiles = $options['inheritFiles'];
			$inheritForums = $options['inheritForums'];
			$inheritPages = $options['inheritPages'];
			$inheritSubGroups = $options['inheritSubGroups'];
		}
		$groupOptions = array('inheritMembers'=>$inheritMembers, 'inheritFiles'=>$inheritFiles, 'inheritForums'=>$inheritForums, 
				 'inheritPages'=>$inheritPages, 'inheritSubGroups'=>$inheritSubGroups);

		//check if a sub-group when parentGroupGuid is null
		if(!isset($parentGroupGuid)) {
			$parentGroup = elgg_get_entities_from_relationship(array(
				"relationship" => "au_subgroup_of",
	   			"relationship_guid" => $guid
			));
			$parentGroupGuid = $parentGroup[0]->guid;
		}
		//get group
		$oldGroup = get_entity($guid);
		//get user
		$user = get_user($oldGroup->owner_guid);
		//create new group
		$newGroup = clone $oldGroup;
		$newGroup->name = $name;
        	$newGroup->subgroupOrder = null;
		$newGroup->save();

		//get all categories associated with the group and associate them with the copied group
		$groupCats = elgg_get_entities(array(
					'type' => 'object',
					'subtype' => 'hjcategory',
					'order_by' => 'e.last_action desc',
					'limit' => 40,
					'full_view' => false,
					'container_guid' => $guid
		));

		foreach($groupCats as $groupCat){
			$newGroupCat = clone $groupCat;
			$newGroupCat->container_guid = $newGroup->guid;
			$newGroupCat->save();
		}
		

		if($inheritPages){

			//clone pages belonging to the old group add add them to their new categories
			$groupPages = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'page_top',
				'order_by' => 'e.last_action desc',
				'limit' => 500,
				'full_view' => false,
				'container_guid' => $guid
			));

			$pagesArr = array();
			foreach($groupPages as $groupPage){
				$pagesArr[] = array();
				//create group pages
				$newGroupPage = clone $groupPage;
				$newGroupPage->container_guid = $newGroup->guid;
				$newGroupPage->access_id = $groupPage->access_id;

				$date = new DateTime();
				$newTimeStamp = $date->getTimestamp();
			
				$newGroupPage->time_created = $newTimeStamp;
				$newGroupPage->time_updated = $newTimeStamp;
				$newGroupPage->last_action = $newTimeStamp;
				if ($newGroupPage->save()) {

				//check in the page becaused the user just saved it
				if($newGroupPage->deleteMetadata("checkedOut")){
					//system_message(elgg_echo('pages:checked_in'));
				}
				else{
					//system_message('Page could not be checked in. It is still locked for editing');
				}
				elgg_clear_sticky_form('page');
				// Now save description as an annotation
				$newGroupPage->annotate('page', $newGroupPage->description, $newGroupPage->access_id);
				//system_message(elgg_echo('pages:saved'));	
				add_to_river('river/object/page/create', 'create', elgg_get_logged_in_user_guid(), $newGroupPage->guid);
				}	

				
				//add tags to sub page
				//if page is tagged in a category, need to update to reflect copied groups category
				if($newGroupPage->universal_categories){
					$categories = elgg_get_entities(array(
						'type' => 'object',
						'subtype' => 'hjcategory',
						'order_by' => 'e.last_action desc',
						'limit' => 40,
						'full_view' => false,
						'container_guid' => $newGroup->guid
					));

					$copyCategories = elgg_get_entities_from_relationship(array(
						'relationship' => HYPECATEGORIES_RELATIONSHIP,
						'relationship_guid' => $groupPage->guid,
						'inverse_relationship' => false,
						'limit' => 150
					));

					foreach($categories as $category){
						foreach($copyCategories as $c){
							if($category->title == $c->title){
								add_entity_relationship($newGroupPage->guid, HYPECATEGORIES_RELATIONSHIP, $category->guid);
							}
						}
					}
				}
					
				$subpages = get_page_children($groupPage,$newGroupPage,$newGroupPage, $newGroup);				

			}
				


		}



		if($inheritFiles){
			//clone files belonging to the old group add add them to their new categories
			$groupFiles = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'file',
				'order_by' => 'e.last_action desc',
				'limit' => 500,
				'full_view' => false,
				'container_guid' => $guid
			));

			foreach($groupFiles as $groupFile){
				$newGroupFile = clone $groupFile;
				$newGroupFile->container_guid = $newGroup->guid;
				$newGroupFile->access_id = $newGroup->group_acl;

				$date = new DateTime();
				$newTimeStamp = $date->getTimestamp();
			
				$newGroupFile->time_created = $newTimeStamp;
				$newGroupFile->time_updated = $newTimeStamp;
				$newGroupFile->last_action = $newTimeStamp;
				$newGroupFile->save();
				
				//copy file over on disk
				$prefix = "file/";
				$filestorename = elgg_strtolower(time().$newGroupFile->originalfilename);
				$sourceDest = $newGroupFile->getFilenameOnFilestore();
				$newGroupFile->setFilename($prefix . $filestorename);
				$newGroupFile->open("write");
				$newGroupFile->close();
				if(!copy($sourceDest, $newGroupFile->getFilenameOnFilestore())){
					error_log("couldn't copy");
				}

				//if file is tagged in a category, need to update to reflect copied groups category
				if($newGroupFile->universal_categories){
					$categories = elgg_get_entities(array(
						'type' => 'object',
						'subtype' => 'hjcategory',
						'order_by' => 'e.last_action desc',
						'limit' => 40,
						'full_view' => false,
						'container_guid' => $newGroup->guid
					));

					$copyCategories = elgg_get_entities_from_relationship(array(
						'relationship' => HYPECATEGORIES_RELATIONSHIP,
						'relationship_guid' => $groupFile->guid,
						'inverse_relationship' => false,
						'limit' => 150
					));

					foreach($categories as $category){
						foreach($copyCategories as $c){
							if($category->title == $c->title){
								add_entity_relationship($newGroupFile->guid, HYPECATEGORIES_RELATIONSHIP, $category->guid);
							}
						}
					}
				}
				//$newGroupFile->save();
			}

			//get all folders associated with the group and associate them with the new copied group
			$groupFolders = elgg_get_entities_from_metadata(array(
						'type' => 'object',
						'subtype' => 'folder',
						'order_by' => 'e.last_action desc',
						'limit' => 120,
						'full_view' => false,
						'container_guid' => $guid,
						'metadata_name' => 'parent_guid',
						'metadata_value' => 0
			));

			foreach($groupFolders as $groupFolder){
				$newGroupFolder = clone $groupFolder;
				$newGroupFolder->container_guid = $newGroup->guid;
				$newGroupFolder->access_id = $newGroup->group_acl;
				$newGroupFolder->save();

				//get files associated with folder
				$files = elgg_get_entities(array(
					'type' => 'object',
					'subtype' => 'file',
					'order_by' => 'e.last_action desc',
					'limit' => 500,
					'full_view' => false,
					'container_guid' => $newGroup->guid,
				));

				$oldFolderFiles = elgg_get_entities_from_relationship(array(
						'relationship' => 'folder_of',
						'relationship_guid' => $groupFolder->guid,
						'inverse_relationship' => false,
						'limit' => 100
				));

				foreach($oldFolderFiles as $f){
					foreach($files as $file){
						if($file->title == $f->title){
							add_entity_relationship($newGroupFolder->guid, 'folder_of', $file->guid);
						}
					}
				}
				
				//get sub folders
				$subFolders = elgg_get_entities_from_metadata(array(
						'type' => 'object',
						'subtype' => 'folder',
						'order_by' => 'e.last_action desc',
						'limit' => 60,
						'full_view' => false,
						'container_guid' => $guid,
						'metadata_name' => 'parent_guid',
						'metadata_value' => $groupFolder->guid
				));

				foreach($subFolders as $subFolder){
					$newSubFolder = clone $subFolder;
					$newSubFolder->container_guid = $newGroup->guid;
					$newSubFolder->access_id = $newGroup->group_acl;
					$newSubFolder->parent_guid = $newGroupFolder->guid;
					$newSubFolder->save();

					$oldSubFolderFiles = elgg_get_entities_from_relationship(array(
						'relationship' => 'folder_of',
						'relationship_guid' => $subFolder->guid,
						'inverse_relationship' => false,
						'limit' => 60
					));

					foreach($oldSubFolderFiles as $f){
						foreach($files as $file){
							if($file->title == $f->title){
								add_entity_relationship($newSubFolder->guid, 'folder_of', $file->guid);
							}
						}
					}

					//get sub sub folders
					$subFolders2 = elgg_get_entities_from_metadata(array(
							'type' => 'object',
							'subtype' => 'folder',
							'order_by' => 'e.last_action desc',
							'limit' => 60,
							'full_view' => false,
							'container_guid' => $guid,
							'metadata_name' => 'parent_guid',
							'metadata_value' => $subFolder->guid
					));

					foreach($subFolders2 as $subFolder2){
						$newSubFolder2 = clone $subFolder2;
						$newSubFolder2->container_guid = $newGroup->guid;
						$newSubFolder2->access_id = $newGroup->group_acl;
						$newSubFolder2->parent_guid = $newSubFolder->guid;
						$newSubFolder2->save();

						$oldSubFolder2Files = elgg_get_entities_from_relationship(array(
							'relationship' => 'folder_of',
							'relationship_guid' => $subFolder2->guid,
							'inverse_relationship' => false,
							'limit' => 60
						));

						foreach($oldSubFolder2Files as $f){
							foreach($files as $file){
								if($file->title == $f->title){
									add_entity_relationship($newSubFolder2->guid, 'folder_of', $file->guid);
								}
							}
						}
						//get sub sub folders
						$subFolders3 = elgg_get_entities_from_metadata(array(
								'type' => 'object',
								'subtype' => 'folder',
								'order_by' => 'e.last_action desc',
								'limit' => 60,
								'full_view' => false,
								'container_guid' => $guid,
								'metadata_name' => 'parent_guid',
								'metadata_value' => $subFolder2->guid
						));

						foreach($subFolders3 as $subFolder3){
							$newSubFolder3 = clone $subFolder3;
							$newSubFolder3->container_guid = $newGroup->guid;
							$newSubFolder3->access_id = $newGroup->group_acl;
							$newSubFolder3->parent_guid = $newSubFolder2->guid;
							$newSubFolder3->save();

							$oldSubFolder3Files = elgg_get_entities_from_relationship(array(
								'relationship' => 'folder_of',
								'relationship_guid' => $subFolder3->guid,
								'inverse_relationship' => false,
								'limit' => 60
							));

							foreach($oldSubFolder3Files as $f){
								foreach($files as $file){
									if($file->title == $f->title){
										add_entity_relationship($newSubFolder3->guid, 'folder_of', $file->guid);
									}
								}
							}

							//get sub sub folders
							$subFolders4 = elgg_get_entities_from_metadata(array(
									'type' => 'object',
									'subtype' => 'folder',
									'order_by' => 'e.last_action desc',
									'limit' => 60,
									'full_view' => false,
									'container_guid' => $guid,
									'metadata_name' => 'parent_guid',
									'metadata_value' => $subFolder3->guid
							));

							foreach($subFolders4 as $subFolder4){
								$newSubFolder4 = clone $subFolder4;
								$newSubFolder4->container_guid = $newGroup->guid;
								$newSubFolder4->access_id = $newGroup->group_acl;
								$newSubFolder4->parent_guid = $newSubFolder3->guid;
								$newSubFolder4->save();

								$oldSubFolder4Files = elgg_get_entities_from_relationship(array(
									'relationship' => 'folder_of',
									'relationship_guid' => $subFolder4->guid,
									'inverse_relationship' => false,
									'limit' => 60
								));

								foreach($oldSubFolder4Files as $f){
									foreach($files as $file){
										if($file->title == $f->title){
											add_entity_relationship($newSubFolder4->guid, 'folder_of', $file->guid);
										}
									}
								}

								//get sub sub sub folders
								$subFolders5 = elgg_get_entities_from_metadata(array(
										'type' => 'object',
										'subtype' => 'folder',
										'order_by' => 'e.last_action desc',
										'limit' => 60,
										'full_view' => false,
										'container_guid' => $guid,
										'metadata_name' => 'parent_guid',
										'metadata_value' => $subFolder4->guid
								));

								foreach($subFolders5 as $subFolder5){
									$newSubFolder5 = clone $subFolder5;
									$newSubFolder5->container_guid = $newGroup->guid;
									$newSubFolder5->access_id = $newGroup->group_acl;
									$newSubFolder5->parent_guid = $newSubFolder5->guid;
									$newSubFolder5->save();

									$oldSubFolder5Files = elgg_get_entities_from_relationship(array(
										'relationship' => 'folder_of',
										'relationship_guid' => $subFolder5->guid,
										'inverse_relationship' => false,
										'limit' => 60
									));

									foreach($oldSubFolder5Files as $f){
										foreach($files as $file){
											if($file->title == $f->title){
												add_entity_relationship($newSubFolder5->guid, 'folder_of', $file->guid);
											}
										}
									}
								}//end 6th level folders
							}//end 5th level folders
						}//end 4th level folders
					}//end 3rd level folders
				}//end 2nd folders
			}//end group folders
		}

		//copy over forums
		if($inheritForums){
			$groupForums = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'hjforum',
				'order_by' => 'e.last_action asc',
				'limit' => 100,
				'full_view' => false,
				'container_guid' => $guid
			));
			$i1 = 0;
			foreach($groupForums as $groupForum){
				$newGroupForum = clone $groupForum;
				$newGroupForum->container_guid = $newGroup->getGUID();
				$newGroupForum->access_id = $newGroup->group_acl;
				$newGroupForum->last_action = time();
				$newGroupForum->save();

				$subForums = elgg_get_entities(array(
					'type' => 'object',
					'subtype' => 'hjforum',
					'order_by' => 'e.last_action asc',
					'limit' => 150,
					'full_view' => false,
					'container_guid' => $groupForum->guid
				));
				
				$i2 = 0;
				foreach($subForums as $subForum){
					$newSubForum = clone $subForum;
					$newSubForum->container_guid = $newGroupForum->getGUID();
					$newSubForum->access_id = $newGroupForum->access_id;
					$newSubForum->last_action = time();
					$newSubForum->save();

					$subForums2 = elgg_get_entities(array(
						'type' => 'object',
						'subtype' => 'hjforum',
						'order_by' => 'e.last_action asc',
						'limit' => 150,
						'full_view' => false,
						'container_guid' => $subForum->guid
					));

					$i3 = 0;
					foreach($subForums2 as $subForum2){
						$newSubForum2 = clone $subForum2;
						$newSubForum2->container_guid = $newSubForum->getGUID();
						$newSubForum2->access_id = $newSubForum->access_id;
						$newSubForum2->save();
						
						$time = time() + $i3;
						update_entity_last_action($newSubForum2->guid,$time);
						$i3++;
					}
					
					$time = time() + $i2;
					update_entity_last_action($newSubForum->guid,$time);
					$i2++;
				}
				
				$time = time() + $i1;
				update_entity_last_action($newGroupForum->guid,$time);
				$i1++;
			}
		}
		
		$newGroup->join($user);

		//get admins from old group and add them to the new one
		$oldAdmins = elgg_get_entities_from_relationship(array(
			"relationship" => "group_admin",
	   		"relationship_guid" => $oldGroup->getGUID(),
			"inverse_relationship" => true,
		));

		foreach($oldAdmins as $admin){
			$newGroup->join($admin);
			add_entity_relationship($admin->getGUID(), "group_admin", $newGroup->getGUID());
		}

		//get members from old group and add them to new group if specified by user
		if($inheritMembers){
			//get collection of old members
			$oldMembers = elgg_get_entities_from_relationship(array(
				"relationship" => "member",
		   		"relationship_guid" => $oldGroup->getGUID(),
		   		"inverse_relationship" => true,
		   		'limit' => 200
			));

			foreach($oldMembers as $oldMember){
				$newGroup->join($oldMember);
			}
		}
		if($inheritSubGroups){
			$subGroups = elgg_get_entities_from_relationship(array(
				"relationship" => "au_subgroup_of",
	   			"relationship_guid" => $guid,
	   			"inverse_relationship" => true,
	   			'limit' => false
			));

			foreach($subGroups as $subGroup) {
				copyGroup($subGroup->guid, "Copy of ".$subGroup->name, $newGroup->guid, $groupOptions);
			}
		}

		if($parentGroupGuid) {
			add_entity_relationship($newGroup->guid, 'au_subgroup_of', $parentGroupGuid);
		}
		add_to_river('river/group/create', 'create', $user->guid, $newGroup->guid, $newGroup->access_id);
		$newGroup->save();
		return $newGroup->getURL();
	}

	$url = copyGroup($guid, $groupName);
	elgg_set_ignore_access($access_level);
	forward($url);
