<?php

namespace model\service;

use model\entity\InfoPulseUser;
use model\entity\SocialType;
use model\entity\InfoPulseUserAccs;
use model\service\TwitterAPIExchange;

use util\Request;

class InfoPulseService{
    
      function AddInfoPulseUser($name){
        $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM `InfoPulseUsers` WHERE UserName REGEXP ?");
        
        $stmt->execute(array('[[:<:]]'.$name.'[[:>:]]'));
        
        $user = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
        if(!empty($user)){
            return NULL;
        }//if
        
        else{
            
            $stmt = \util\MySQL::$db->prepare("INSERT INTO InfoPulseUsers(id,UserName) VALUES(NULL,:name)");
            $stmt->bindParam(':name',$name);
            $res = $stmt->execute();
            
            $stmt = \util\MySQL::$db->prepare("SELECT * FROM `InfoPulseUsers` WHERE UserName LIKE :user_name");
            $stmt->bindParam(':user_name',$name);
            $stmt->execute();

            $user = $stmt->fetchObject('model\entity\InfoPulseUser');
            
            return $user;
            
        }//else
        
      }//AddInfoPulseUser
      
      function UpdateInfoUser($id,$newName){
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM `InfoPulseUsers` WHERE UserName LIKE :user_name");
          $stmt->bindParam(':user_name',$newName);
          $stmt->execute();
          
          if($stmt->fetch(\PDO::FETCH_OBJ)){
              return NULL;
          }//if
          
          else{
              
              $stmt = \util\MySQL::$db->prepare("UPDATE `InfoPulseUsers` SET UserName = :newName WHERE id = :uid");
              $stmt->bindParam(':newName',$newName);
              $stmt->bindParam(':uid',$id);
              
              return $stmt->execute();
              
          }
      }
      
      function DeleteInfoPulseUser($id){
          
          $stmt = \util\MySQL::$db->prepare("DELETE FROM `InfoPulseUsers` WHERE id = :id");
          $stmt->bindParam(':id',$id);
          return $stmt->execute();
          
      }
      
      function GetAllInfoUsers(){
           $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
        $stmt->execute();
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM  `InfoPulseUsers` ");
          $stmt->execute();
          
          $info_users = [];
          
          while($info_user = $stmt->fetchObject('model\entity\InfoPulseUser')){
              $info_users[] = $info_user;
          }//while
          
          foreach($info_users as $user){
              $user->Socials = $this->GetSocialsFromSpecialUser($user->id);
          }
          
          return $info_users;
          
      }
      
      function AddSocialToUser($user_id,$social_id,$accs_name){
          $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
          $stmt->execute();
        
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM InfopulseUsersAccs WHERE UserId = :uid and SocialId = :sid and AccsName LIKE :accname");
          $stmt->bindParam(':uid',$user_id);
          $stmt->bindParam(':sid',$social_id);
          $stmt->bindParam(':accname',$accs_name);
          $stmt->execute();
          
          if($stmt->fetch(\PDO::FETCH_OBJ)){
              return NULL;
          }//if
          else{
              $stmt = \util\MySQL::$db->prepare("INSERT INTO InfopulseUsersAccs(id,UserId,SocialId,AccsName) VALUES(NULL,:uid,:sid,:accname)");
              $stmt->bindParam(':uid',$user_id);
              $stmt->bindParam(':sid',$social_id);
              $stmt->bindParam(':accname',$accs_name);
              
              return $stmt->execute();
              
          }//else
          
      }
      
      function GetSocialTypes(){
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM  `SocialType` ");
          $stmt->execute();
          
          $socials = [];
          
          while($social = $stmt->fetchObject('model\entity\SocialType')){
              $socials[] = $social;
          }//while
          
          return $socials;
          
          
      }
      
      function AddSocialType($SocialName){

          $stmt = \util\MySQL::$db->prepare("SELECT * FROM `SocialType` WHERE SocialName REGEXP ?");
          $stmt->execute(array('[[:<:]]'.$SocialName.'[[:>:]]'));
          
          if($stmt->fetch(\PDO::FETCH_OBJ)){
              return NULL;
          }//if
          
          else{
              
               $stmt = \util\MySQL::$db->prepare("INSERT INTO SocialType(id,SocialName) VALUES(NULL,:name)");
               $stmt->bindParam(':name',$SocialName);
               $res = $stmt->execute();
               
               $stmt = \util\MySQL::$db->prepare("SELECT * FROM `SocialType` WHERE SocialName REGEXP ?");
               $stmt->execute(array('[[:<:]]'.$SocialName.'[[:>:]]'));
               
               $social = $stmt->fetchObject('model\entity\SocialType');
               
               return $social;
              
          }
          
      }
      
      function GetSocialsFromSpecialUser($id){
          
          $stmt = \util\MySQL::$db->prepare("SET NAMES utf8");
          $stmt->execute();
          
          $stmt = \util\MySQL::$db->prepare("SELECT InfopulseUsersAccs.id, InfopulseUsersAccs.UserId, SocialType.SocialName AS SocialId, InfopulseUsersAccs.AccsName FROM InfopulseUsersAccs INNER JOIN SocialType ON SocialType.id = InfopulseUsersAccs.SocialId WHERE InfopulseUsersAccs.UserId = :id");
          $stmt->bindParam(':id',$id);
          $stmt->execute(); 
          
          $InfopulseUsersAccs = [];
          
          while($social = $stmt->fetchObject('model\entity\InfoPulseUserAccs')){
              $InfopulseUsersAccs[] = $social;
          }//while
          
          return $InfopulseUsersAccs;
          
      }
      
      function DeleteSocial($id){
          
          $stmt = \util\MySQL::$db->prepare("DELETE FROM `InfopulseUsersAccs` WHERE SocialId = :id");
          $stmt->bindParam(':id',$id);
          $stmt->execute();
          
          $stmt = \util\MySQL::$db->prepare("DELETE FROM `SocialType` WHERE id = :id");
          $stmt->bindParam(':id',$id);
          return $stmt->execute();
          
      }
      
      function UpdateSocial($id,$newTitle){
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM  `SocialType` WHERE SocialName LIKE :title ");
          $stmt->bindParam(':title',$newTitle,\PDO::PARAM_STR);
          $stmt->execute();
          
          if($stmt->fetch(\PDO::FETCH_OBJ)){
              return NULL;
          }//if
          else{
              
              $stmt = \util\MySQL::$db->prepare("UPDATE `SocialType` SET SocialName = :name WHERE id = :id");
              $stmt->bindParam(':name',$newTitle);
              $stmt->bindParam(':id',$id);
              
              return $stmt->execute();
              
          }//else
          
      }
      function DeleteUserAcc($id){
          
            $stmt = \util\MySQL::$db->prepare("DELETE FROM `InfopulseUsersAccs` WHERE id = :id");
            $stmt->bindParam(':id',$id);
            return $stmt->execute();
            
      } 
      
      
}