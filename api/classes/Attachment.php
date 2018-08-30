<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Attachment
 *
 * @author McFarlane.a
 */
class Attachment {
	public $title;
	public $url;
	public $mimeType;
	
	public function __construct($obj)
	{
		$this->title = $obj->title;
		$this->url = "file/download/$obj->guid";
		$this->mimeType = $obj->mimetype;
	}
}
