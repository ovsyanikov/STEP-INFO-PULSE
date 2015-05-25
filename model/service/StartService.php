<?php

namespace model\service;

class StartService{
    
    public function __construct() {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
    }
    
}