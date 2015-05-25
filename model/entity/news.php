<?php

namespace model\entity;

class news{
    
    private $id;
    private $Title;
    private $Files;
    private $Owner;
    private $Description;
    private $PublicDateTime;
  
    
    function getFiles() {
        return $this->Files;
    }

    function getOwner() {
        return $this->Owner;
    }

    function getDescription() {
        return $this->Description;
    }

    function getPublicDateTime() {
        return $this->PublicDateTime;
    }

    function __construct() {
        
    }
    
    function setFiles($Files) {
        $this->Files = $Files;
    }

    function setOwner($Owner) {
        $this->Owner = $Owner;
    }

    function setDescription($Description) {
        $this->Description = $Description;
    }

    function setPublicDateTime($PublicDateTime) {
        $this->PublicDateTime = $PublicDateTime;
    }

        function getId() {
        return $this->id;
    }
     
    
    function getTitle() {
        return $this->Title;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitle($Title) {
        $this->Title = $Title;
    }




    
}