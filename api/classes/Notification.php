<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notification
 *
 * @author McFarlane.a
 */
class Notification {
	private $toId;
	private $subject;
	private $body;
	
	public function __construct($toId, $subject, $body)
	{
		$this->toId = $toId;
		$this->subject = $subject;
		$this->body = $body;
	}
	
	public function send()
	{
		return notify_user($this->toId, elgg_get_site_entity()->guid, $this->subject, $this->body, NULL, 'email');
	}
}
