<?php
/*
* NewFeatureTour Class
*******************************
*/
class NewFeatureTour extends ElggObject{

	function initializeAttributes(){
		parent::initializeAttributes();

		$this->userGuid = $this->getUserGuid();
		$this->user = $this->getUser();
	}

	function __construct(){
		$this->initializeAttributes();
	}

	protected function getUserGuid(){
		return elgg_get_logged_in_user_guid();
	}

	protected function getUser(){
		if($this->userGuid){
			return get_entity($this->userGuid);
		}
	}

	public function hasReadDialog($feature){
		if($this->user->$feature){
			return true;
		}
		else{
			return false;
		}
	}

	public function createDialog($text, $feature){
		/*$content = "<div id='dialog-new-feature' title='New Feature'>
	  					<p>$text</p>
	  				</div>";
	  	echo $content;*/
	  	$this->markAsRead($feature);
	}

	public function markAsRead($feature){
		if($this->user){
			$this->user->$feature = true;
			$this->user->save();
		}
	}
}