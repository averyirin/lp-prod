<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Language
 *
 * @author McFarlane.a
 */
class Language {
	//put your code here
	public $plugin;
	
	public function __construct($plugin, $session)
	{
		$this->plugin = $plugin;
		$this->currentLanguage = $session->getLanguage();
	}
	 
	public function getObjects()
	{
		$json = $this->readLanguageFile();
	}
	
	public function readLanguageFile()
	{
		$base_dir = elgg_get_data_path() . "translation_editor" . DIRECTORY_SEPARATOR;
			
		if(file_exists($base_dir . $this->currentLanguage . DIRECTORY_SEPARATOR . $this->plugin . ".json")){
			if($contents = file_get_contents($base_dir . $current_language . DIRECTORY_SEPARATOR . $plugin . ".json")){
				$result = json_decode($contents, true);
			}

		}
	}
}
