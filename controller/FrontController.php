<?php

namespace controller;

use util\Request;
use util\View;

class FrontController{
    
    private $view;
    private $controller;
    
    function start(){
        
        GLOBAL $CONTROLLERS_NAMESPACE;
        GLOBAL $DEFAULT_PAGE ;
        GLOBAL $DEFAULT_CONTROLLER;
        GLOBAL $DEFAULT_ACTION;
        
        $db_name = \util\MySQL::GetDbName();
        $db_user = \util\MySQL::GetUserName();
        $db_user_password = \util\MySQL::GetUserPassword();
        
        \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);
        $this->view = new View();
        
        session_start();
        $r = new Request();
        
        $control = $r->getGetValue('ctrl');
        $action = $r->getGetValue('act');
        
        if(empty($control)){
            $control = $DEFAULT_CONTROLLER;
        }//if
        
        if(empty($action)){
            $action = $DEFAULT_ACTION;
        }//if
            
            $control[0] = strtoupper($control[0]);
            
            $class = "{$control}Controller";
            
            if(file_exists("$CONTROLLERS_NAMESPACE".DIRECTORY_SEPARATOR."$class.php")){
              
               $class = "$CONTROLLERS_NAMESPACE\\{$control}Controller";
               $CONTROLLER = new $class($this->view);
               
               if( !method_exists($CONTROLLER, "{$action}Action") ) {
                   
                    header("Location: ?ctrl=$DEFAULT_CONTROLLER&act=$DEFAULT_ACTION");

               }//if
               else{
                    
                    $view = $CONTROLLER->{"{$action}Action"}();

               }//else
                $folder = strtolower($control);
                include "view/$folder/$view.php";
            }//if
            else{
                header("Location: ?ctrl=$DEFAULT_CONTROLLER&act=$DEFAULT_ACTION");
            }//else
        
    }//start

}