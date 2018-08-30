<?php

/**
 * Description of Entity
 *
 * @author AlexMc
 */
class Entity extends ElggEntity{
    
    public function __construct($id, $session)
    {
        parent::initializeAttributes();
        $this->session = $session;
        $this->isGroup = false;
        
        if($id) {
            parent::load($id);
            
            if(elgg_instanceof($this, "group")) {
                $this->isGroup = true;
            }
        }
    }
    
    public function getAll()
    {
        $arr = array();
        
        $arr['id'] = $this->getGUID();
        $arr['access_id'] = $this->getAccessID();
        $arr['group_acl'] = $this->group_acl;
        $arr['is_group'] = $this->isGroup;
        
        return $arr;
    }
}
