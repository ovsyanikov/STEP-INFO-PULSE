<?php

namespace model\entity;

class PasswordToRecovery{
    
    public $UserID;
    public $AccessToken;
    public $TimeRecovery;
    
    function __construct() {
        
    }

    function getUserID() {
        return $this->UserID;
    }

    function getAccessToken() {
        return $this->AccessToken;
    }

    function getTimeRecovery() {
        return $this->TimeRecovery;
    }

    function setUserID($UserID) {
        $this->UserID = $UserID;
    }

    function setAccessToken($AccessToken) {
        $this->AccessToken = $AccessToken;
    }

    function setTimeRecovery($TimeRecovery) {
        $this->TimeRecovery = $TimeRecovery;
    }




}