<?php

/*
 * To change this license header, choose License Headers in File Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of File
 *
 * @author
 */
class File extends FilePluginFile {
    private $file = array();
	private $title;
    private $description;
    private $folder_id;
    public $session;
    
	
	public function __construct($id = null, $obj = null, $session)
	{
        $this->session = $session;
        
		if($id) {
           parent::__construct($id);
        }
        else if($obj) {
            parent::initializeAttributes();
            $this->newObj($obj);
        }
	}
    
    private function newObj($obj)
    {
        if(empty($obj['title'])) {
            $obj['title'] = htmlspecialchars($obj['file']['name'], ENT_QUOTES, 'UTF-8');
        }
        
        $this->attributes['subtype'] = 'file';
        $this->file = $obj['file'];
        $this->setTitle($obj['title']);
        $this->setDescription($obj['description']);
        $this->setFolderId((int) $obj['folder_id']);
        $this->setAccessId($obj['access_id']);
        $this->setContainerGUID($obj['container_guid']);
        $this->setTags($obj['tags']);
    }
    
    public function validate()
    {
        $flag = true;
        if(is_null($this->getFile())) {
            $this->session->errors['file'] = elgg_echo('file:nofile');
            $flag = false;
        }
        if(is_null($this->getAccessId())) {
            $this->session->errors['access_id'] = 'You must supply an access id';
            $flag = false;
        }
        if(is_null($this->getfolderId())) {
            $this->session->errors['folder_id'] = 'You must supply a folder id';
            $flag = false;
        }
        return $flag;
    }
    
    public function create()
    {
        try{
            $this->setFileStore($this->file);
            $this->save();
            $this->setFolderRelationship();
            $this->addToRiver();
            return true;
        } catch (Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
	private function setTitle($title)
	{
		$this->attributes['title'] = $title;
	}
    
    public function setDescription($description)
    {
        $this->attributes['description'] = $description;
    }

    public function setFolderId($folderId)
    {
        $this->folder_id = $folderId;
    }
    
    public function setAccessId($accessId)
    {
        $this->attributes['access_id'] = $accessId;
    }
    
    public function setTags($tags)
    {
        if($tags){
            $this->tags = string_to_tag_array($tags);
        }
    }
    
    public function setFileStore($file = array())
    {
        $prefix = "file/";
        $fileStoreName = elgg_strtolower(time().$file['name']);
        
        //set file name
        $this->setFilename($prefix . $fileStoreName);
        
        //detect MIME type
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

        if($ext == "ppt"){
            $mime_type = 'application/vnd.ms-powerpoint';
        }
        else{
            $mime_type = ElggFile::detectMimeType($file['tmp_name'], $file['type'], $file['name']);
        }

        // hack for Microsoft zipped formats
        $info = pathinfo($file['name']);
        $office_formats = array('docx', 'xlsx', 'pptx');
        if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
            switch ($info['extension']) {
                case 'docx':
                    $mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                    break;
                case 'xlsx':
                    $mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                    break;
                case 'pptx':
                    $mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
                    break;
            }
        }

        // check for bad ppt detection
        if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
            $mime_type = "application/vnd.ms-powerpoint";
        }

        if($mime_type == "application/vnd.ms-powerpoint"){
            $mime_type ="application/vnd.openxmlformats-officedocument.presentationml.presentation";
        }

        $this->setMimeType($mime_type);
        $this->originalfilename = $file['name'];
        $this->simpletype = file_get_simple_type($mime_type);

        // Open the file to guarantee the directory exists
        $this->open("write");
        $this->close();
        move_uploaded_file($file['tmp_name'], $this->getFilenameOnFilestore());
        
        if($this->simpletype = 'image') {
            //create image thumbnail
            $this->setThumbnail($file, $prefix, $fileStoreName);
        }
        elseif ($this->icontime) {
            // if it is not an image, we do not need thumbnails
            unset($this->icontime);

            $thumb = new ElggFile();

            $thumb->setFilename($prefix . "thumb" . $fileStoreName);
            $thumb->delete();
            unset($this->thumbnail);

            $thumb->setFilename($prefix . "smallthumb" . $fileStoreName);
            $thumb->delete();
            unset($this->smallthumb);

            $thumb->setFilename($prefix . "largethumb" . $fileStoreName);
            $thumb->delete();
            unset($this->largethumb);
        }
    }
    
    private function setThumbnail($file = array(),  $prefix, $fileStoreName)
    {
        $this->icontime = time();

        $thumbnail = get_resized_image_from_existing_file($this->getFilenameOnFilestore(), 60, 60, true);
        if ($thumbnail) {
            $thumb = new ElggFile();
            $thumb->setMimeType($file['type']);

            $thumb->setFilename($prefix."thumb".$fileStoreName);
            $thumb->open("write");
            $thumb->write($thumbnail);
            $thumb->close();

            $this->thumbnail = $prefix."thumb".$fileStoreName;
            unset($thumbnail);
        }

        $thumbsmall = get_resized_image_from_existing_file($this->getFilenameOnFilestore(), 153, 153, true);
        if ($thumbsmall) {
            $thumb->setFilename($prefix."smallthumb".$fileStoreName);
            $thumb->open("write");
            $thumb->write($thumbsmall);
            $thumb->close();
            $this->smallthumb = $prefix."smallthumb".$fileStoreName;
            unset($thumbsmall);
        }

        $thumblarge = get_resized_image_from_existing_file($this->getFilenameOnFilestore(), 600, 600, false);
        if ($thumblarge) {
            $thumb->setFilename($prefix."largethumb".$fileStoreName);
            $thumb->open("write");
            $thumb->write($thumblarge);
            $thumb->close();
            $this->largethumb = $prefix."largethumb".$fileStoreName;
            unset($thumblarge);
        }
    }

    private function setFolderRelationship()
    {
        if(!empty($this->folder_id)) {
            if($folder = get_entity($this->folder_id)) {
                if(!elgg_instanceof($folder, "object", FILE_TOOLS_SUBTYPE)) {
                    unset($this->folder_id);
                }
            } else {
                unset($this->folder_id);
            }
        }

        // remove old relationships
        remove_entity_relationships($this->getGUID(), FILE_TOOLS_RELATIONSHIP, true);

        if(!empty($this->folder_id)) {
            add_entity_relationship($this->folder_id, FILE_TOOLS_RELATIONSHIP, $this->getGUID());
            //update parent folders last_action attribute
            update_entity_last_action($this->folder_id);
            $parent_guid = get_entity($this->folder_id)->parent_guid;
            if($parent_guid){
                update_parent_folder_last_action($parent_guid);
            }
        }
    }
    
    private function addToRiver()
    {
        try{
            add_to_river('river/object/file/create', 'create', $this->owner_guid, $this->getGUID());
        }
        catch(Exception $e) {
            $this->session->errors[] = $e->getMessage();
            return false;
        }
    }
    
    public function getFile()
    {
        return $this->file;
    }

    public function getTitle()
    {
        return $this->title;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getFolderId()
    {
        return $this->folder_id;
    }

    public function delete() {
        elgg_set_ignore_access();
        return parent::delete();
    }

    public function getAll()
    {
        $arr = array();
        
        $arr['id'] = $this->guid;
        $arr['title'] = $this->title;
        $arr['tags'] = $this->tags;
        
        return $arr;
    }

}
