<?php

namespace model\service;
use util\Request;
use model\entity\news;
use model\entity\resource_news;
use model\service\GlobalService;
use model\entity\global_news;

class NewsService{
    
      function PublicPost($owner){
          
          $r = new Request();
          
          $post_title = $r->getPostValue('postTitle');
          
          $post_description = $r->getPostValue('makePostArea');
          
          $dis_id = $r->getPostValue('SelectedDistrictId');
          
          $dis_str = $r->getPostValue('SelectedDistrict');
          
          $user_files;
          $name = NULL;
          
        try {

          foreach($_FILES['user_files']['name'] as $k=>$f) {

          if (!$_FILES['user_files']['error'][$k]) {

                 if (is_uploaded_file($_FILES['user_files']['tmp_name'][$k])) {

                      if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['user_files']['name'][$k])){

                          $file_array = explode('.',$_FILES['user_files']['name'][$k]);
                          $ext = end($file_array);

                          $name = "$owner.". md5($name . rand (-100000,100000)).".$ext" ;
                          $user_files  .= ($name.",");

                          if (move_uploaded_file($_FILES['user_files']['tmp_name'][$k], "files/".$name)) {
                              }//if
                          }//if
                      }//if
          }//if
      }//foreach

        } 
        catch (Exception $ex) {}
        
        $glob = new GlobalService();
        $new_global_news = new global_news();
        
        $new_global_news->setTitle($post_title);
        $new_global_news->setDescription($post_description);
        if(strlen( $user_files ) != 0){
            $new_global_news->setImage($user_files);
        }//if
        else{
            $new_global_news->setImage(NULL);
        }
        $new_global_news->setSource($owner);
        $date = date("d.m.o H:i",  strtotime("now"));
        $new_global_news->setDistrict(intval($dis_id));
        $new_global_news->setDate($date);
        if(strlen($dis_str) != 0){
            $new_global_news->setDistrict_str($dis_str);
        }
        else{
            $new_global_news->setDistrict_str("Район не указан");
        }
        $new_global_news->setStop_words('Запись пользователя');   
        $new_global_news->setSearchType('i');
        $res = $glob->AddGlobalNews($new_global_news);
        
        return $res;
        
      }
      
      function GetMyPosts(){
          
          $news = [];
          
          $r = new Request();
          $owner = $r->getSessionValue('user_info_plus');
          if(empty($owner)){
              $owner = $r->getCookieValue('user_info_plus');
          }
          
          $stmt = \util\MySQL::$db->prepare("SET NAMES UTF8");
          $stmt->execute();
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE Source LIKE ?");
          $stmt->execute(array("%$owner%"));
          
          while ($current_news = $stmt->fetchObject('model\entity\global_news')){
              
                 $news[] = $current_news;
                 
          }//while

          return $news;
          
      }//GetMyPosts
      
      function GetSpecificNews(){
          
          $r = new Request();
          
          $news_id = $r->getGetValue('news_id');
           $stmt = \util\MySQL::$db->prepare("SET NAMES UTF8");
           $stmt->execute();
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM news WHERE id = :id");
          $stmt->bindParam(":id",$news_id);
          $stmt->execute();
          $spec_news = $stmt->fetchObject('model\entity\news');
          if(is_a($spec_news, 'model\entity\news')){
              return $spec_news;
          }
          else{
              return NULL;
          }
          
      }
      
      function GetLastResourceNews(){
          
          $last_news = [];
          
          $stmt = \util\MySQL::$db->prepare("SET NAMES UTF8");
          $stmt->execute();
          
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM ResourceNews LIMIT 5");
          $stmt->execute();
          
          while($news = $stmt->fetchObject(resource_news::class) ){
              
              $last_news[] = $news;
              
          }//while
          
          return $last_news;
          
      }//GetLastResourceNews
      
      function GetMyTasks(){
          
          $news = [];
          
          $r = new Request();
          $owner = $r->getSessionValue('user_info_plus');
          if(empty($owner)){
              $owner = $r->getCookieValue('user_info_plus');
          }
           $stmt = \util\MySQL::$db->prepare("SET NAMES UTF8");
            $stmt->execute();
        
          $stmt = \util\MySQL::$db->prepare("SELECT * FROM global_news WHERE title Like ? or description Like ? and SearchType = 'i'");
          $params = array("%@$owner%","%@$owner%");
          $stmt->execute($params);
          
          while ($current_news = $stmt->fetchObject('model\entity\global_news')){
              
                 $news[] = $current_news;
                 
          }//while

          return $news;
      }
      
}