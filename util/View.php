<?php

namespace util;

class View{
    
    private $storage = [];
    
    function __get($name) {
        
        if(isset($this->storage[$name])){
            return $this->storage[$name];
        }//if
        
        return NULL;
        
    }//__get
    
    function __set($name, $value) {
        
       $this->storage[$name] = $value;
               
    }//__get
}
