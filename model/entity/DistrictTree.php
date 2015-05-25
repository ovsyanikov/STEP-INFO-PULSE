<?php

namespace model\entity;

class DistrictTree {
    
    public $DistrictId;
    public $ChildDistictId;
     
    
    public function __construct() {
        
    }
    
    function getDistrictId() {
        return $this->DistrictId;
    }

    function getChildDistictId() {
        return $this->ChildDistictId;
    }

    function setDistrictId($DistrictId) {
        $this->DistrictId = $DistrictId;
    }

    function setChildDistictId($ChildDistictId) {
       $this->ChildDistictId = $ChildDistictId;
    }


    
    
}
