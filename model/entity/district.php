<?php

namespace model\entity;

class district{
    
    public $id;
    public $Title;
    public $Date;
    
    function __construct() {
        
    }
    function getId(){return $this->id;}
    function getTitle(){return $this->Title;}
}