<?php

namespace controller;

use model\service\NewsService;
use model\service\UserService;
use model\service\GlobalService;
use model\service\InfoPulseService;
use util\Request;
use model\entity\global_news;
use model\entity\stopword;
use model\entity\SocialInfo;
use model\entity\bad_word;

class NewsController extends \controller\BaseController{
    
    private $startService;
    private $newsService;
    private $userService;
    private $globalService;
    
    public function newsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен

            $user = $this->getRequest()->getSessionValue('user_info_plus');
            if(empty($user)){
                $user = $this->getRequest()->getCookieValue('user_info_plus');
            }//

            $this->view->current_user = $this->GetUserService()->getUser($user);
            $user_serv->authorize($this->view->current_user->getLogin(),$this->view->current_user->getPassword());
            $id = $_COOKIE["last_post_id"];
            if($_COOKIE["last_post_id"]){
                $this->view->all_news = $glob_sevice->GetLastGlobalNews($id);
            }else{
                $this->view->all_news = $glob_sevice->GetGlobalNews(0,10);
            }
            
            $this->view->vk_posts = $glob_sevice->GetVkPostsCount();
            $this->view->tw_posts = $glob_sevice->GetTwitterPostsCount();
            $this->view->google_posts = $glob_sevice->GetGooglePostsCount();
            $this->view->google_news_posts = $glob_sevice->GetGoogleNewsPostsCount();
            $this->view->fb_posts = $glob_sevice->GetFaceBookPostsCount();
            $this->view->ya_posts = $glob_sevice->GetYandexPostsCount();
            $this->view->lj_posts = $glob_sevice->GetLjPostsCount();
            $this->view->ShowAllHiddenNews = $user_serv->getUserMetaValue('show_all_hidden_news',$this->view->current_user->id);
            $this->view->count_of_new_news =  $glob_sevice->GetNewNewsCount();
            $this->view->count_of_new_tasks = $user_serv->getCountOfNewTasks($this->view->current_user->id);
                    
           //$this->getRequest()->setCookiesWithKey('offset',0);
           
           return 'news';
           
       }//else
    }
    
    public function downloadAction() {
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен

            $user = $this->getRequest()->getSessionValue('user_info_plus');
            if(empty($user)){
                $user = $this->getRequest()->getCookieValue('user_info_plus');
            }//

            $this->view->current_user = $this->GetUserService()->getUser($user);
            $user_serv->authorize($this->view->current_user->getLogin(),$this->view->current_user->getPassword());
            
            $this->view->vk_posts = $glob_sevice->GetVkPostsCount();
            $this->view->tw_posts = $glob_sevice->GetTwitterPostsCount();
            $this->view->google_posts = $glob_sevice->GetGooglePostsCount();
            $this->view->google_news_posts = $glob_sevice->GetGoogleNewsPostsCount();
            $this->view->fb_posts = $glob_sevice->GetFaceBookPostsCount();
            $this->view->ya_posts = $glob_sevice->GetYandexPostsCount();
            $this->view->lj_posts = $glob_sevice->GetLjPostsCount();
            $r = new Request();
        
            $file_name = $r->getGetValue('filename');
            if(!empty($file_name)){
                $directory = 'documents/';

                if(file_exists($directory.$file_name)){

                    $content = file_get_contents($directory.$file_name);
                    header('Content-Type: docx; charset=utf-8');
                    header("Content-Disposition: attachment; filename=".$directory.$file_name);
                    ob_end_clean();
                    ob_start();
                    echo $content;
                    ob_end_flush();

                }//if
                else{
                    echo "<div style=\"color: white\">FILE $directory$file_name NOT FOUND</div>";
                }//else
            }//if
           
       }//else
    }
    
    public function StatisticAction() {
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен

            $user = $this->getRequest()->getSessionValue('user_info_plus');
            if(empty($user)){
                $user = $this->getRequest()->getCookieValue('user_info_plus');
            }//if
            
            $this->view->current_user = $this->GetUserService()->getUser($user);
            $user_serv->authorize($this->view->current_user->getLogin(),$this->view->current_user->getPassword());
            
            $this->view->vk_posts = $glob_sevice->GetVkPostsCount();
            $this->view->tw_posts = $glob_sevice->GetTwitterPostsCount();
            $this->view->google_posts = $glob_sevice->GetGooglePostsCount();
            $this->view->google_news_posts = $glob_sevice->GetGoogleNewsPostsCount();
            $this->view->fb_posts = $glob_sevice->GetFaceBookPostsCount();
            $this->view->ya_posts = $glob_sevice->GetYandexPostsCount();
            $this->view->lj_posts = $glob_sevice->GetLjPostsCount();
            $this->view->district_table = $glob_sevice->GetStatisticByDistricts();
            $this->view->stop_words_table = $glob_sevice->GetStatisticByStopWords();
            
            return 'Statistic';
           
       }//else
        
        
    }
    
    public function UsersActivityAction(){
        
        
        $this->view->userActivities = $this->GetUserService()->getUserActivities();
        
        return 'UsersActivity';
        
    } 
    
    public function VkGroupSettingsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       $infoService = new InfoPulseService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           $this->view->groups = $glob_sevice->GetVkGroups();
           
           $this->view->vk_auth = $glob_sevice->GetVkAuthLink();
           $this->view->info_users = $infoService->GetAllInfoUsers();
           $this->view->social_types = $infoService->GetSocialTypes();
           $this->view->access_token = $glob_sevice->GetAccessToken();
           $this->view->user = $glob_sevice->GetUserId();
            
           return 'VkGroupsSettings';
           
       }//else
    }

    public function FbUsersSettingsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           $this->view->fb_users = $glob_sevice->GetFbUsers();
           
           $this->view->vk_auth = $glob_sevice->GetVkAuthLink();
           
           $this->view->access_token = $glob_sevice->GetAccessToken();
           $this->view->user = $glob_sevice->GetUserId();
            
           return 'FbUsersSettings';
           
       }//else
    }    

    public function TwitterUsersSettingsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           $this->view->twitter_users = $glob_sevice->GetTwitterUsers();
           
           $this->view->tw_auth = $glob_sevice->get_twitter_oauth_link();
           
           $this->view->access_token = $glob_sevice->GetAccessToken();
           $this->view->user = $glob_sevice->GetUserId();
            
           return 'TwitterUsersSettings';
           
       }//else
    }        
    
    public function PersonOfInterestAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->news_by_interests = $glob_sevice->GetALLNewsByInterests();
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
            
           return 'PersonOfInterest';
           
       }//else
    }
    
    public function getNewsByStopWordsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $global_service = new GlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           $this->view->districts = $global_service->GetDistricts();
           
           $r = new Request();
           
           $stop_words = $this->GetGlobalService()->GetStopWords();
           $distr =  $global_service->GetDistrictByName($r->getPostValue('District'));
           
           $news = [];
           
           foreach($stop_words as $word){
               
               $word = trim($word->getWord());
               $news[] = $global_service->GetGlobalNewsByStopWord($word, $distr->getId(),0,10);
               
           }//foreach
           
           $this->view->finded_news = $news;
           $this->view->stop_words = $this->GetGlobalService()->GetStopWords();
           $r->setCookiesWithKey('currecnt_district', $distr->getTitle());
           
           return 'Districts';
           
       }//else'
       
        
    }
    
    public function GetNewsBySearchWordAction() {
        
         $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $global_service = new GlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $r = new Request();
           $word = $r->getGetValue('SearchWord');
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
            $this->view->user_word = $word;
           $this->view->finded_news = $global_service->GetNewsBySerachWord($word);
           
           return 'NewsBySearchWord';
           
        }//else
        
    }
    
    public function SpecificPostHomeAction(){
       
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $global_service = new GlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           $r = new Request();
           
           $post_id = $r->getGetValue('id');
           $this->view->post_comments = $global_service->GetCommentsToPost($post_id);
           
           if(!empty($post_id)){
               $specific_news =  $global_service->GetGlobalNewsById($post_id);
           }
           else{
               $post_id = $r->getGetValue('PersonId');
               $specific_news =  $global_service->GetPersonNewsById($post_id);
           }
           
           $bw = $global_service ->GetBadWords();
           
           if(is_a($specific_news,'model\entity\global_news')){
               
               $this->view->bw = $bw;
               $this->view->global_news = $specific_news;
               $this->view->stop_words = $global_service->GetStopWords();
               
               return 'SpecificPostHome';
           }//if
           else{
               return 'NewsNotFound';
           }//else
           
       }//else
        
    }
    
    public function specificPostAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           $this->view->current_user = $this->GetUserService()->getUser($user);
           return 'SpecificPost';
       }//else
        
    }
    
    public function MakePostAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           return 'MakePost';
       }//else
        
        
    }
    
    public function GetVkNewsAction() {
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $glob_sevice = $this->GetGlobalService();
       $stop_word_for_search = $glob_sevice->GetStopWords();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           
           ini_set("max_execution_time", "2500");
           
           $districts = $glob_sevice->GetDistricts();
           
           $i=0;

           foreach ($districts as $district){//Проходим по всем районам

                //&start_time=".(time()-299)."
                $d_title = $district->getTitle();
                $to_search = urlencode($d_title);
                $result = file_get_contents("https://api.vk.com/method/newsfeed.search?q=$to_search&extended=0&count=90&v=5.28");      

                $result_from_json = json_decode($result);

                foreach ($result_from_json->response->items as $my_item){

                    if($my_item->owner_id < 0){//Отсеивание групп
                        $pos = false;
                        //Описание новости
                        $text = $my_item->text;

                        foreach($stop_word_for_search as $sw){
                            //поиск в тексте стоп-слова, если тру останавлеваем поиск, сохранаяем запись в базе
                            $pos = stripos($text, $sw->getWord());
                            if($pos  != false){
                                break;
                            }//if            
                            
                        }//foreach
                        if ($pos != false){

                            $date = date("D M Y H:i:s",$my_item->date);
                            //Заголовок
                            $title = explode('.', $text)[0];
                            $contains = false;
                            $contains = $glob_sevice->IsContainsNews($title);

                            if($contains < 10){

                            if(strlen($title) > 100){

                                $title = substr($title, 0, 97);
                                $title .= "...";

                            }//if

                            $img = NULL;

                            if(property_exists($my_item, 'attachments')){
                                $att = $my_item->attachments[0];

                                if(property_exists($att,'photo')){
                                    $photo = $my_item->attachments[0]->photo;
                                    if(property_exists($photo,'photo_1280')){
                                        $img = $my_item->attachments[0]->photo->photo_1280;
                                    }//if
                                    else if(property_exists($photo,'photo_604')){
                                        $img = $my_item->attachments[0]->photo->photo_604;
                                    }
                                }//if
                            }//if

                            $new_global_news = new global_news();
                            $new_global_news->setTitle($title);
                            $new_global_news->setDescription($text);
                            $new_global_news->setImage($img);
                            $new_global_news->setSource("http://vk.com/feed?w=wall{$my_item->owner_id}_{$my_item->id}");
                            $new_global_news->setDistrict($district->getId());
                            $new_global_news->setDate($date);
                            $new_global_news->setDistrict_str($district->getTitle());
                            $new_global_news->setStop_words($sw->getWord());   

                            $glob_sevice->AddGlobalNews($new_global_news);
                            $i++;

                            }//if


                        }//if стоп-слова
                    }//if группы   

                }//foreach

            }//foreach
            
           $this->view->all_news = $glob_sevice->GetGlobalNews(0,10);
           $this->view->vk_posts = $glob_sevice->GetVkPostsCount();
           $this->view->tw_posts = $glob_sevice->GetTwitterPostsCount();
           $this->view->google_posts = $glob_sevice->GetGooglePostsCount();
           $this->view->fb_posts = $glob_sevice->GetFaceBookPostsCount();
           
           $this->getRequest()->setCookiesWithKey('offset',0);
           $this->getRequest()->setCookiesWithKey('vk_records',$i);
           
           return 'news';
           
       }//else
        
    }//GetVkNewsAction
    
    public function MyPostsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           $this->view->current_user = $this->GetUserService()->getUser($user);
           $this->view->current_user_news =  $this->GetNewsService()->GetMyPosts();
           return 'MyPosts';
       }//else
    }
    
    public function MyTasksAction() {
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           $this->view->my_tasks = $this->GetNewsService()->GetMyTasks();
           
           return 'MyTasks';
           
       }//else
        
        
    }
    
    public function ConfirmPostAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           
           $owner = $this->view->current_user->getLogin();
           $res = $this->GetNewsService()->PublicPost($owner);
           $this->view->result = $res;
           
           $this->view->current_user_news =  $this->GetNewsService()->GetMyPosts();
           
           return 'MyPosts';
       }//else
        
    }
    
    public function SpecificNewsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
            $user = $this->getRequest()->getSessionValue('user_info_plus');
            if(empty($user)){
                $user = $this->getRequest()->getCookieValue('user_info_plus');
            }//if
            $this->view->current_user = $this->GetUserService()->getUser($user);
            $spec_news = $this->GetNewsService()->GetSpecificNews();
            if($spec_news != null){
                
                $this->view->specific_news = $spec_news;
                
                return 'SpecificNews';
            }//if
            else{
                return 'NewsNotFound';
            }//else
            
       }//else
    }
    
    public function SettingAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       $global_service = new GlobalService();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           $this->view->districts = $global_service->GetDistricts();
           $this->view->stop_words = $global_service->GetStopWords();
           $this->view->bad_words = $global_service->GetBadWords();
           
           $r = new Request();
           
           return 'Settings';
           
       }//else'
        
    }
    
    public function DistrictsAction(){
        
       $user_serv = $this->GetUserService();
       $access = $user_serv->isAccessDenied();
       
       if($access){//Если доступ запрещен
           $this->redirect("index");
       }//if
       else{//Если доступ разрешен
           
           $user = $this->getRequest()->getSessionValue('user_info_plus');
           
           if(empty($user)){
               $user = $this->getRequest()->getCookieValue('user_info_plus');
           }//if
           
           $this->view->current_user = $this->GetUserService()->getUser($user);
           $this->view->all_count = $this->GetGlobalService()->GetAllNewsCount();
           $this->view->districts = $this->GetGlobalService()->GetDistricts();
           $this->view->stop_words = $this->GetGlobalService()->GetStopWords();
           $this->getRequest()->setCookiesWithKey('offset',0);
           
           return 'Districts';
       }//else
        
    }
    
    public function GetUserService(){
        
        if(empty($this->userService)){
            $this->userService = new UserService();
        }//if
        
        return $this->userService;
        
    }
    
    public function GetNewsService(){
        
        if(empty($this->newsService)){
            $this->newsService = new NewsService();
        }//if
        
        return $this->newsService;
    }
    
    public function GetGlobalService() {
        
        if(empty($this->globalService)){
            
            $this->globalService = new GlobalService();
            
        }//if
        return $this->globalService;
    }
    
    
}