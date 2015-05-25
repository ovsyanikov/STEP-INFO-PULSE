<?php

namespace util;

class Request{
    
    function isPost(){
        return !empty($_POST);   
    }
    
    function getPostValue($name){
        return filter_input(INPUT_POST, $name, FILTER_SANITIZE_STRING);
    }
    
    function getGetValue($name){
        return filter_input(INPUT_GET, $name, FILTER_SANITIZE_STRING);
    }
    
    function getCookieValue($name){
        
        if(isset($_COOKIE[$name])){
            return filter_input(INPUT_COOKIE, $name, FILTER_SANITIZE_STRING);
        }
        
    }
    
    function getSessionValue($value){
        
        if(isset($_SESSION[$value])){
            return $_SESSION[$value];
        }
        
    }
    
    function unsetCookie($name){
        
        if(isset($_COOKIE[$name])){
            setcookie($name, '');
        }//if
    }
            
    function setSessionValue($name,$value){
        $_SESSION[$name] = $value;
    }

    function unsetSeesionValue($name){
        
        $_SESSION[$name] = '';
        
    }
    
    function setCookies($value){
        
        setcookie('user_info_plus', $value, time() + 86400);
        
    }
    
    function setCookiesWithKey($key,$value){
        
        setcookie($key, $value, time() + 86400);
        
    }
    
}