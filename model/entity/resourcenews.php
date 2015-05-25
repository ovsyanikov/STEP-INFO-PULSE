<?php

namespace model\entity;

class resource_news{
    
    private $id;
    private $title;
    private $description;
    private $Public_Date;
    
    function __construct() {
        
    }
    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getPublic_Date() {
        return $this->Public_Date;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPublic_Date($Public_Date) {
        $this->Public_Date = $Public_Date;
    }


    
}