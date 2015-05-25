<?php

namespace controller;

use model\entity\user;
use model\service\UserService;
use model\service\StartService;

class UserController extends BaseController{
    
      private $userService;
      
      public function ChangePasswordAction(){
          
          return 'ChangePassword';
          
      }
      
      function leaveAction(){
          
          if(empty($this->userService)){
              
              $this->userService = new UserService();
              $this->userService->leaveResource();
              
          }//if
          $this->redirect("index");
          
      }//leaveAction
      
      function recoveryAction(){
          
          return 'PasswordRecovery';
          
      }//leaveAction
      
      function authorizeAction() {
        
        if(empty($this->userService)){
              
              $this->userService = new UserService();
              
          }//if
          $r = new \util\Request();
              
          $login = $r->getPostValue("userLE");
          $password = $r->getPostValue("userPS");
          $remember = $r->getPostValue("remember_me");
          
          $cuser = $this->userService->authorize($login, $password);
          
          
          if($cuser != NULL){
                  
                  $this->view->current_user = $cuser;
                  $lpc = $this->userService->getLastTimeChangePassword($cuser->getId());
                  
                  if($remember == 'on'){
                      
                      $r->setCookies($cuser->getLogin());
                      $r->setCookiesWithKey('lpc',$lpc->getLastChangePassword());
                      $r->setSessionValue('user_info_plus', $cuser->getLogin());
                      $r->setSessionValue('lpc', $lpc->getLastChangePassword());
                      
                  }//if
                  else{
                      
                      $r->setSessionValue('user_info_plus', $cuser->getLogin());
                      $r->setSessionValue('lpc', $lpc->getLastChangePassword());
                       
                  }//else
                  
                  header("Location: ?ctrl=news&act=news");
                  
          }//if
          else{
              
              return 'user_not_found';
              
          }
    }
    
      function mainRegisterAction(){
          
          //Регистрация на главной (welcome.php)
          
          $newUser = new user();
          $r = new \util\Request();
         
          $newUser->setEmail($r->getPostValue('REmail'));
          $newUser->setLogin($r->getPostValue('RLogin'));
          $newUser->setPassword($r->getPostValue('Rpassword'));
          
          $this->view->NewUser = $newUser;
          
          return 'register';
          
      }
      
      function newuserAction(){
          
          if(empty($this->userService)){
              
              $this->userService = new UserService();
              
          }//if
          
          $r = new \util\Request();
          $newUser = new user();
          
          $login = $r->getPostValue("userLE");
          $newUser->setLogin($login);
          
          $password = $r->getPostValue("userPS");
          $newUser->setPassword($password);
          
          $email = $r->getPostValue("REmail");
          $newUser->setEmail($email);
          
          $firstName = $r->getPostValue("NewFirstName");
          $newUser->setFirstName($firstName);
          
          $lastName = $r->getPostValue("NewLastName");
          $newUser->setLastName($lastName);
          
          $this->userService->add($newUser);
          
          header("Location: ?ctrl=news&act=news");
          
      }//newuserAction
      
      function MyProfileAction(){ 
          
            $user_serv = $this->GetUserService();
            $access = $user_serv->isAccessDenied();
       
            if($access){//Если доступ запрещен
                $this->redirect("index");
            }//if
            else{//Если доступ разрешен
                $user = $this->getRequest()->getSessionValue('user_info_plus');
                if(empty($user)){
                    $user = $this->getRequest()->getCookieValue('user_info_plus');
                }//
                $this->view->current_user = $this->GetUserService()->getUser($user);
                return 'MyProfile';
                
            }//else

      }//MyProfileAction
      
      public function GetUserService(){
        
        if(empty($this->userService)){
            $this->userService = new UserService();
        }//if
        
        return $this->userService;
        
    }
    
}//UserController