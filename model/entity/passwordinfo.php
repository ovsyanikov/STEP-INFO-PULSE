<?php

namespace model\entity;

class passwordinfo{
    
    private $id;
    private $user;
    private $LastChangePassword;
    
    function getId() {
        return $this->id;
    }

    function __construct() {
        
    }
    function setId($id) {
        $this->id = $id;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setLastChangePassword($LastChangePassword) {
        $this->LastChangePassword = $LastChangePassword;
    }

        
    function getUser() {
        return $this->user;
    }

    function getLastChangePassword() {
        return $this->LastChangePassword;
    }


    
}
