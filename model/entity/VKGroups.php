<?php
namespace model\entity;

class VKGroups{
    
    public $id;
    public $GroupTitleId;
    
    function getId() {
        return $this->id;
    }

    function getGroupTitleId() {
        return $this->GroupTitleId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setGroupTitleId($GroupTitleId) {
        $this->GroupTitleId = $GroupTitleId;
    }

    function __construct() {
        
    }

    
}
