<?php

namespace model\entity;

class district{
    
    public $id;
    public $Title;
    
    function __construct() {
        
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