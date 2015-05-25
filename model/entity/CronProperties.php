<?php

namespace model\entity;

class CronProperties{
    
    private $TimeStart;
    private $TimeEnd;
    private $Offset;
    
    function __construct() {
        
    }
    
    function getTimeStart() {
        return $this->TimeStart;
    }

    function getTimeEnd() {
        return $this->TimeEnd;
    }

    function getOffset() {
        return $this->Offset;
    }

    function setTimeStart($TimeStart) {
        $this->TimeStart = $TimeStart;
    }

    function setTimeEnd($TimeEnd) {
        $this->TimeEnd = $TimeEnd;
    }

    function setOffset($Offset) {
        $this->Offset = $Offset;
    }

}