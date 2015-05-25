<?php

namespace model\entity;

class UsersEnterExit {
    
    public $UserId;
    public $TimeEnter;
    public $TimeExit;
    
    public function __construct() {
        
    }
    
    function getUserId() {
        return $this->UserId;
    }

    function getTimeEnter() {
        return $this->TimeEnter;
    }

    function getTimeExit() {
        return $this->TimeExit;
    }

    function setUserId($UserId) {
        $this->UserId = $UserId;
    }

    function setTimeEnter($TimeEnter) {
        $this->TimeEnter = $TimeEnter;
    }

    function setTimeExit($TimeExit) {
        $this->TimeExit = $TimeExit;
    }


    
    
   
}
