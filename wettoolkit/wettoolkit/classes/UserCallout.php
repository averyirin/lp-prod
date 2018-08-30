<?php
class UserCallout extends ElggObject{
	
	function initializeAttributes(){
		parent::initializeAttributes();
	}

	function __construct($fromUser, $calloutUsers, $context, $contextUrl){
		$this->initializeAttributes();

		$this->fromUser = $fromUser;
		$this->calloutUsers = $calloutUsers;
		$this->context = $context;
		$this->url = $contextUrl;
	}

	public function sendUserNotifications(){
		if(is_array($this->calloutUsers)){
			foreach($this->calloutUsers as $calloutUserGuid){
				notify_user($calloutUserGuid, $this->fromUser->guid, $this->fromUser->name." mentioned you in ".$this->context, "You have been mentioned in ".$this->context.". View it here: ".$this->url);
			}
		}
		else{
			notify_user($this->calloutUsers, $this->fromUser->guid, $this->fromUser->name." mentioned you in ".$this->context, "You have been mentioned in ".$this->context.". View it here: ".$this->url);
		}
	}
}
?>