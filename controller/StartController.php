<?php

namespace controller;

use model\service\StartService;
use model\service\UserService;

class StartController extends \controller\BaseController{
    
    private $startService;
    private $userService;
    
    public function welcomeAction(){
        
        if(empty($this->startService)){
            
            $this->startService = new StartService();
            
        }
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           return 'welcome';
       }
       else{//Если доступ разрешен
           
         if(isset($_GET['code'])){
             header("Location: index?ctrl=news&act=VkGroupSettings&code={$_GET['code']}");
         }
         else{header("Location: index?ctrl=news&act=news");}
         
       }
       
    }
    
    public function GetStartService(){
        
        
        if(empty($this->startService)){
            
            $this->startService = new StartService();
            
        }
     
        return $this->startService;
        
    }
    
    public function GetUserService(){
        
        
          
        if(empty($this->userService)){
            
            $this->userService = new UserService();
            
        }
     
        return $this->userService;
        
    }
}