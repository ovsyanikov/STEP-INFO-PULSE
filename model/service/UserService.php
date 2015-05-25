<?php

namespace model\service;

use model\entity\user;
use model\entity\passwordinfo;
use model\entity\UsersEnterExit;
use model\entity\UserInfo;
use model\service\GlobalService;
use model\entity\PasswordToRecovery;

use util\Request;

class UserService{
    
    function getCountOfTasks(){
        $r = new Request();
        $owner = $r->getSessionValue('user_info_plus');
        if(empty($owner)){
            $owner = $r->getCookieValue('user_info_plus');
        }
        
        $stmt = \util\MySQL::$db->prepare("SELECT COUNT(*) FROM global_news WHERE title Like ? or description Like ? and SearchType = 'user'");
        $params = array("%@$owner%","%@$owner%");
        $stmt->execute($params);
        
        return $stmt->fetch(\PDO::FETCH_BOTH)[0];
    }
    
    
    function getCountOfNewTasks($user_id){
        
        $tasks_count = $this->getCountOfTasks();
        
        $count = $this->getUserMetaValue('new_tasks',$user_id);
        
        if($count == NULL){//нет записей в мете
           $this->setUserMetaValue('new_tasks',$user_id,$tasks_count);
        }//if
        else{
            
            if($tasks_count > $count){
                
                $count = $tasks_count-$count;
                $this->setUserMetaValue('new_tasks',$user_id,$count);
                
            }//if
            else{
                $this->setUserMetaValue('new_tasks',$user_id,0);
            }
        }//else
        
        
        return $this->getUserMetaValue('new_tasks',$user_id);
    }
    
    function getUserMetaValue($meta_key, $user_id){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM user_meta WHERE User_Id = :uid and Meta_Key = :key ");
        $stmt->bindParam(':uid',$user_id);
        $stmt->bindParam(':key',$meta_key);
        
        $stmt->execute();
        
        $user_meta = $stmt->fetch(\PDO::FETCH_OBJ);
        if(!empty($user_meta)){
            
            return $user_meta->Meta_Value;
            
        }
        else{
            return NULL;
            
        }
        
    }
    
    function setUserMetaValue($meta_key, $user_id, $meta_value){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM user_meta WHERE User_Id = :uid and Meta_Key = :key");
        $stmt->bindParam(':uid',$user_id);
        $stmt->bindParam(':key',$meta_key);
        $stmt->execute();
        
        $user_meta = $stmt->fetch(\PDO::FETCH_OBJ);
        if( $user_meta != NULL){
            
            $stmt = \util\MySQL::$db->prepare("UPDATE user_meta SET Meta_Value = :val WHERE User_Id = :uid and Meta_Key = :key");
            
            $stmt->bindParam(':val',$meta_value);
            $stmt->bindParam(':uid',$user_id);
            $stmt->bindParam(':key',$meta_key);
            
        }
        else{
            
            $stmt = \util\MySQL::$db->prepare("INSERT INTO user_meta(Id,User_Id,Meta_Key,Meta_Value) VALUES(NULL,:uid,:key,:val)");
            $stmt->bindParam(':uid',$user_id);
            $stmt->bindParam(':key',$meta_key);
            $stmt->bindParam(':val',$meta_value);
            
        }
        $res = $stmt->execute();
        
        return $res;
    }
    
    function getUserActivities(){ 
        
        $stmt = \util\MySQL::$db->prepare("SELECT `RegisterDT`, `Login` ,  `TimeEnter` FROM  `UsersEnterExit` INNER JOIN  `users` ON  `UsersEnterExit`.`UserId` =  `users`.`id` ");
        
        $stmt->execute();
        
        $users_activity = [];
        
        
        while($user = $stmt->fetchObject('model\entity\UserInfo')){
            $users_activity[] = $user;
        }//while
        
        return $users_activity;
        
    }
    
    function add(user $user) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
            
        $stmt = \util\MySQL::$db->prepare("INSERT INTO users (id,Login,Password,Email,FirstName,LastName,RegisterDT) VALUES(NULL,:login,:pass,:email,:fn,:ln,now())");
        
        $login = $user->getLogin();
        $pass = $user->getPassword();
        $email = $user->getEmail();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        
        $stmt->bindParam(":login",$login);
        $stmt->bindParam(":pass",$pass);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":fn",$firstName);
        $stmt->bindParam(":ln",$lastName);
        
        $stmt->execute();
        
        $r = new \util\Request();
        $r->setSessionValue('user_info_plus', $user->getLogin());
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :login");
        $stmt->bindParam(":login",$login);
        $stmt->execute();
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user,'model\entity\user')){
            
            $id_user = $user->getId();
            
        }//if
        
        $stmt = \util\MySQL::$db->prepare("INSERT INTO passwordinfo (id,user,LastChangePassword) VALUES(NULL,:user,NOW())");
        $stmt->bindParam(":user",$id_user);
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM passwordinfo WHERE user = :id_user");
        $stmt->bindParam(":id_user",$id_user);
        $stmt->execute();
        
        $ps_info = $stmt->fetchObject(passwordinfo::class);
        
        if(is_a($ps_info,'model\entity\passwordinfo')){
            $last_time = $ps_info->getLastChangePassword();
        }//if
        
        $r->setSessionValue('lpc', $last_time);
        
    }//add
            
    function authorize($login, $password) {
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE ( (Login = :login or Email =:login) and Password = :pass)");
        $stmt->bindParam(":login",$login);
        $stmt->bindParam(":pass",$password);
        
        $stmt->execute();
        
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user, 'model\entity\user')){
            
            $r = new Request();
            $glob_service = new GlobalService();
            
            $user_id = $user->getId();
            
            $r->setCookiesWithKey('current_user',$user_id);
            $r->setCookiesWithKey('show_hidden_news',$this->getUserMetaValue('show_all_hidden_news',$user_id));
            
            $stmt = \util\MySQL::$db->prepare("SELECT * FROM UsersEnterExit WHERE UserId = :id");
            $id = $user->getId();
            
            $stmt->bindParam(":id",$id);
            $stmt->execute();
            $userInfo = $stmt->fetchObject('model\entity\UsersEnterExit');
            
            if(is_a($userInfo,'model\entity\UsersEnterExit')){//Если есть информация о посищении сайта пользователем
                
                $stmt = \util\MySQL::$db->prepare("UPDATE UsersEnterExit SET TimeEnter = now() WHERE UserId = :id");
                $stmt->bindParam(":id",$id);
                $stmt->execute();
                
            }//if
            else{//Если нет информации о посищении сайта пользователем
                
                $stmt = \util\MySQL::$db->prepare("INSERT INTO UsersEnterExit(UserId,TimeEnter,TimeExit) VALUES(:id,now(),NULL)");
                $stmt->bindParam(":id",$id);
                $stmt->execute();
            }
            return $user;
            
        }
        else{
            
            return NULL;
            
        }
        
    }
    
    function GetAccessToken($user_id){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM PasswordRecovery WHERE UserID = :id");
        $stmt->bindParam(':id',$user_id);
        $stmt->execute();
        
        $userInfo = $stmt->fetchObject(PasswordToRecovery::class);
        return $userInfo->AccessToken;
        
    }
    
    function GenerateNewPassword($id){
        
        $NewPassword = md5($id.rand(-10000, 10000));
        
        $globalService = new GlobalService();
        $userInfo = $globalService->GetRecoveryByUserId($id);
        
        if(is_a($userInfo,'model\entity\PasswordToRecovery')){
            return FALSE;
        }//if
        
        else{
            
            $stmt = \util\MySQL::$db->prepare("SELECT Email FROM `users` WHERE id = :id");
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $mail = $stmt->fetch(\PDO::FETCH_BOTH)[0];
            
            $headers = 'From: INFO-PULSE@mail.ru' . "\r\n";

            $mail_result = mail($mail,'Восстановление пароля. INFO-PULSE','Ваш код доступа для смены пароля - ' . $NewPassword ,$headers);
            
            $stmt = \util\MySQL::$db->prepare("INSERT INTO PasswordRecovery(UserID,AccessToken,TimeRecovery) VALUES(:id,:at,now())");
            $stmt->bindParam(':id',$id);
            $stmt->bindParam(':at',$NewPassword);
            $stmt->execute();
            
            $r = new Request();
            $r->setCookiesWithKey('user_recovery',$id);
            
            return $mail_result;
            
        }//else
        
        
        
    }
    
    function UpdateUserPassword($id, $userPassword){
        
        $stmt = \util\MySQL::$db->prepare("UPDATE users SET Password = :pass WHERE id = :id");
        $stmt->bindParam(":pass",$userPassword);
        $stmt->bindParam(":id",$id);
        $res = $stmt->execute();
        
        if($res > 0){
            
            $stmt = \util\MySQL::$db->prepare("DELETE FROM PasswordRecovery WHERE UserID = :id");
            $stmt->bindParam(":id",$id);
            $res = $stmt->execute();
            
            return TRUE;
        }
        else{
            return FALSE;
        }
        
    }
    
    function leaveResource(){
        
        $r = new \util\Request();
        
        $userLogin = $r->getCookieValue('user_info_plus');
        $userLoginSession = $r->getSessionValue('user_info_plus');
        
        
        $r->unsetCookie('user_info_plus');
        $r->unsetCookie('lpc');
        $r->unsetSeesionValue('user_info_plus');
        $r->unsetSeesionValue('lpc');
        
        if(!empty($userLogin)){
            $finalUserLogin = $userLogin;
        }
        else{
            $finalUserLogin = $userLoginSession;
        }
        
        $user = $this->getUser($finalUserLogin);
        $id = $user->getId();
        
        $stmt = \util\MySQL::$db->prepare("UPDATE UsersEnterExit SET TimeExit = now() WHERE UserId = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        
    }
    
    function getUserById($id){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(":id",$id);
        
        $stmt->execute();
        $user = $stmt->fetchObject('model\entity\user');
        
        if(is_a($user, 'model\entity\user')){
           return $user;
        }//if
        else {
            
            return NULL;
            
        }
        
    }
    
    function getUser($login){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :login or Email = :mail");
        $stmt->bindParam(":login",$login);
        $stmt->bindParam(":mail",$login);
        
        $stmt->execute();
        $user = $stmt->fetchObject('model\entity\user');
        
        if(is_a($user, 'model\entity\user')){
           return $user;
        }//if
        else {
            
            return NULL;
            
        }
    }
    
    function getAllUsers(){
        
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users");
        
        $stmt->execute();
        $users = [];
        while($user = $stmt->fetchObject('model\entity\user')){
            $users[] = $user;
        }
        
        return $users;
        
    }
    
    function getLastTimeChangePassword($user_id){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM passwordinfo WHERE user = :id_user");
        $stmt->bindParam(":id_user",$user_id);
        $stmt->execute();
        
        $ps_info = $stmt->fetchObject(passwordinfo::class);
        
        if(is_a($ps_info,'model\entity\passwordinfo')){
            return $ps_info;
        }//if
        else{
            return NULL;
        }//else
        
    }
    
    function isAccessDenied(){
        
        $r = new \util\Request();
        
        $is_user_cookies = $r->getCookieValue('user_info_plus');
        $lpcc = $r->getCookieValue('lpc');
        $lpcs = $r->getSessionValue('lpc');
        $is_user_session = $r->getSessionValue('user_info_plus');
        
        if($is_user_cookies == NULL && $is_user_session == NULL && $lpcc == NULL && $lpcs==NULL){
            
            return 'welcome';
            
        }//if
        
        if(!empty($lpcc)){//LastPassword Change not empty in cookies
            
            if(!empty($is_user_cookies)){//Not Empty user login

                $cookies_user = $this->getUser($is_user_cookies);
                
                if(is_a($cookies_user,'model\entity\user')){
                    
                    $last_time_ps_change = $this->getLastTimeChangePassword($cookies_user->getId());
                    
                    if(is_a($last_time_ps_change,'model\entity\passwordinfo')){
                        
                        if($lpcc == $last_time_ps_change->getLastChangePassword()){
                            
                            return false;
                            
                        }//if
                        else{
                            $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                            return true;
                        }//else
                    }//if
                    else{
                         $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                        return true;
                    }//else
                    
                }//if
                else{
                     $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                    return true;
                }//else
            }//if
            else{
                 $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                return true;
            }//else
            
        }//if
        else if(!empty($lpcs)){//LastPassword Change not empty in session
            
            if(!empty($is_user_session)){//Not Empty user login
                
                $session_user = $this->getUser($is_user_session);
                
                if(is_a($session_user,'model\entity\user')){
                    
                    $last_time_ps_change = $this->getLastTimeChangePassword($session_user->getId());
                    
                    if(is_a($last_time_ps_change,'model\entity\passwordinfo')){
                        
                        if($lpcs == $last_time_ps_change->getLastChangePassword()){
                            
                            return false;
                            
                        }//if
                        else{
                             $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                            return true;
                        }//else
                    }//if
                    else{
                         $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                        return true;
                    }//else
                    
                }//if
                else{
                     $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                    return true;
                }//else
            }//if
            else{
                 $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
                return true;
            }//else
            
        }//else if
        else{
             $r->unsetCookie('user_info_lus');
                            $r->unsetCookie('lpc');
                            $r->unsetSeesionValue('user_info_lus');
                            $r->unsetSeesionValue('lpc');
            return true;
        }//else
        
    }//isAccessDenied
}