<?php

namespace model\entity;

class SocialInfo{
    
    public  $LastRecordId;
    
    public function __construct() {
        
    }
    
    function getLastRecordId() {
        return $this->LastRecordId;
    }

    function setLastRecordId($LastRecordId) {
        $this->LastRecordId = $LastRecordId;
    }


    
}