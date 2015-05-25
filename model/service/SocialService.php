<?php

namespace model\service;

use model\service\InfoPulseService;
use model\service\GlobalService;

use model\entity\InfoPulseUser;
use model\entity\SocialType;
use model\entity\global_news;
use model\service\TwitterAPIExchange;

class SocialService{
    
    public static $vk_constant = 'vk';
    public static $twitter_constant = 'twitter';
    
    function GetWallVkontakte(){
 
        $info_service = new InfoPulseService();
        $glob_service = new GlobalService();
        
        $tk = $glob_service-> GetAccessToken();
        
        $info_users = $info_service->GetAllInfoUsers();
        $user_vk_screen_name = NULL;
        
        foreach($info_users as $user){
            
            foreach ($user->Socials as $social) {
            
                if(stristr($social->SocialId,SocialService::$vk_constant)){
                    $user_vk_screen_name = $social->AccsName;
                    echo "<div>Поиск по $user_vk_screen_name</div>";
                            
                    break;
                    
                }//if
                
            }//foreach
            
            if($user_vk_screen_name != NULL){
                
                $result = file_get_contents("https://api.vk.com/method/wall.get?access_token=$tk&domain=$user_vk_screen_name&count=100&v=5.29");
                
                $result_from_json = json_decode($result);
                
                $this->ScanVkResult($result_from_json);
                
            }//if
            
        }
        
    }//GetWallVkontakte
    
    function GetWallTwitter(){
        
        $i=1;
        
        $info_service = new InfoPulseService();
        
                
        $info_users = $info_service->GetAllInfoUsers();
        $user_tw_screen_name = NULL;
        
        foreach($info_users as $user){
            
            foreach ($user->Socials as $social) {
            
                if(stristr($social->SocialId,SocialService::$twitter_constant)){
                    $user_tw_screen_name = $social->AccsName;
                    echo "<div>Поиск по $user_tw_screen_name</div>";
                            
                    break;
                    
                }//if
                
            }//foreach
            
            if($user_tw_screen_name != NULL){
                
                $this->MakeTwitterRequest($user_tw_screen_name);
                
            }//if
            
        }
        
    }//GetWallTwitter
    
    function MakeTwitterRequest($user_tw_login){
        
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $glob_service = new GlobalService();
        
        $stop_word_for_search = $glob_service->GetStopWords();
        $districts = $glob_service->GetDistricts();
        
        $settings = array(
           'oauth_access_token' => "3062725937-L6VtUnZ6xx644GWDU2Y3NHhz14yx1KADWeAnoxm",
           'oauth_access_token_secret' => "Q54JmVltQyKZjE5ymPAuCcWsipCOLo5GOfFWeUuLpdhqo",
           'consumer_key' => "lW5B5TUxOdwjKxVN9ufGEmYLy",
           'consumer_secret' => "BiJCp5uwPJ8bjufMzDbgRl4P7IzdhH0uawjr31hHHkhkdavYe4"
        );   

        $getfield = "?screen_name=$user_tw_login&count=200";
        $user_screen_name = $user_tw_login;

        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);

        $fields = $twitter->setGetfield($getfield);
        $oAuth = $fields->buildOauth($url, $requestMethod);
        $response = $oAuth->performRequest();
        $js_obj = json_decode($response);
        
        foreach($js_obj as $status){

            //начинаем проход по всем записям юзера

            $text = $status->text;
            $found = false;
            echo "Юзер №$i $user_screen_name <br/> $text <br/><br/>";                

            //найдем вхождение района в тексте
            foreach ($districts as $district){

                $dist = $district->getTitle();
                $dist = trim($dist);
                
                //проверим вхождение района
                if ($dist === mb_strtoupper("$dist",'Utf-8')){
                    $temp_text = $text;

                    $words_sao = strtok($temp_text, ' .,_(){}!@#$%:;+?');
                    $dis_sim = false;

                    while($words_sao !== FALSE){

                        if(strlen($words_sao) == strlen($dist)){
                           if(stristr($words_sao, $dist)){
                               $dis_sim = true;
                               break;

                           } //if
                        }//if

                         $words_sao = strtok(' .,_(){}!@#$%:;+?');
                    }//while

                }
                else{
                    //echo "Не аббревиатура <br/>";
                    $dis_sim = $glob_service->IsSimilar($text,$dist);
                }
                if($dis_sim){
                    break;
                }
            }//for districts

            //если район подошел проверяем стоп слова
            if($dis_sim){
                echo "<span class=\"bold-distr\">Район подошел</span><br/>";

                foreach($stop_word_for_search as $sw){
                    //поиск в тексте стоп-слова, если тру останавлеваем поиск, сохранаяем запись в базе
                    $stop_word = trim( $sw->getWord() );
                    if($glob_service->IsSimilar($text,$stop_word)==true){
                        echo "Стоп слово подошло <span class=\"bold\">$stop_word</span> <br/>";
                        $pos = true;
                        break;

                    }//if
                    else{
                        $pos = false;
                    }
                }//foreach по стоп словам

                if ($pos == true){

                    $user_id = $status->user->id;
                    $screen_name = $status->user->screen_name;
                    $user_image = $status->user->profile_image_url_https;
                    $created_at = $status->created_at;
                    $created_at = strtotime($created_at);

                    $created_at = date("d.m.o H:i",$created_at);

                    $source = "https://twitter.com/" . $status->user->id_str . "/status/" . $status->id_str;

                    $date = $status->created_at;

                    $title = iconv_substr($text, 0,15, 'Utf-8').'...';
                    $text ='<a href="https://twitter.com/'.$screen_name.'" title="Ссылка на пользователя" class="tw_user_href">@' .$screen_name.'</a>: '.$text;

                    $new_global_news = new global_news();
                    $new_global_news->setTitle($title);
                    $new_global_news->setDescription($text);
                    $new_global_news->setSource($source);
                    $new_global_news->setDistrict($district->getId());
                    $new_global_news->setDate($created_at);
                    $new_global_news->setDistrict_str($district->getTitle());
                    $new_global_news->setStop_words($sw->getWord());

                    if(@property_exists($status->entities, 'media')){
                        $media = $status->entities->media;
                            if(@property_exists($media, 'media_url')){
                                $media_url = $media->media_url;

                                if($media_url != NULL){
                                $new_global_news->setImage($status->entities->media->media_url);

                                }//if
                                else{

                                   $new_global_news->setImage($user_image);  

                                }//else
                        }//media_url
                        else{

                            $new_global_news->setImage($user_image);  

                        }//else

                    }//if media

                    else{

                        $new_global_news->setImage($user_image);  

                    }//else
                    $new_global_news->setSearchType('i');
                    $description = $new_global_news->getDescription();


                    if(strlen($description) > 15){
                        $description = iconv_substr($description, 0, 15 , 'UTF-8');
                    }
                    else{
                        $description = iconv_substr($description, 0, 8 , 'UTF-8');
                    }
                    $glob_service->AddGlobalNews2($new_global_news);
                    
                    $new_global_news->setSearchType($screen_name);
                    $glob_service->AddToPersonOfInterest($new_global_news);

                    echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";  


                }//if стоп слово подошло

            }//if вход в проверку по стоп словам
            //не подошел район или стоп слово, запишем в таблицу PersonOfInterest
            else{
                    $user_id = $status->user->id;
                    $screen_name = $status->user->screen_name;
                    $user_image = $status->user->profile_image_url_https;
                    $created_at = $status->created_at;
                    $created_at = strtotime($created_at);

                    $created_at = date("d.m.o H:i",$created_at);

                    $source = "https://twitter.com/" . $status->user->id_str . "/status/" . $status->id_str;

                    $date = $status->created_at;

                    $title = iconv_substr($text, 0,15, 'Utf-8').'...';
                    $text ='<a href="https://twitter.com/'.$screen_name.'" title="Ссылка на пользователя" class="tw_user_href">@' .$screen_name.'</a>: '.$text;

                    $new_global_news = new global_news();
                    $new_global_news->setTitle($title);
                    $new_global_news->setDescription($text);
                    $new_global_news->setSource($source);
                    $new_global_news->setDistrict(0);
                    $new_global_news->setDate($created_at);
                    $new_global_news->setDistrict_str('');
                    $new_global_news->setStop_words('');

                    if(@property_exists($status->entities, 'media')){
                        $media = $status->entities->media;
                            if(@property_exists($media, 'media_url')){
                                $media_url = $media->media_url;

                                if($media_url != NULL){
                                $new_global_news->setImage($status->entities->media->media_url);

                                }//if
                                else{

                                   $new_global_news->setImage($user_image);  

                                }//else
                        }//media_url
                        else{

                            $new_global_news->setImage($user_image);  

                        }//else

                    }//if media

                    else{

                        $new_global_news->setImage($user_image);  

                    }//else
                    $new_global_news->setSearchType('i');
                    $description = $new_global_news->getDescription();


                    if(strlen($description) > 15){
                        $description = iconv_substr($description, 0, 15 , 'UTF-8');
                    }
                    else{
                        $description = iconv_substr($description, 0, 8 , 'UTF-8');
                    }
                    $new_global_news->setSearchType($screen_name);
                    $glob_service->AddToPersonOfInterest($new_global_news);
                
            }
            echo "<br/><br/>";

    }//if по записям юзера

        $settings = array(
            'oauth_access_token' => "3062725937-Kw9iEiRVS8BdCoJs73qbrDoBtsdy8HWLe61P8b9",
            'oauth_access_token_secret' => "r3maoXVB4IW9KLymu0hgGPrNneoicA2AdThIUqH3Eyu4l",
            'consumer_key' => "tNw1sIS6Xa0F2InUcSVsUfsbl",
            'consumer_secret' => "DEceuesToCQGp64CHKJI4XHbWSzBjecjIQLdMAGukMJ6luhbnY"
        );
        
    }
    
    function ScanVkResult($result_from_json){
        
        $glob_service = new GlobalService();
        $districts = $glob_service->GetDistricts();
        $stop_word_for_search = $glob_service->GetStopWords();
        
        foreach ($result_from_json->response->items as $my_item){

        //начинаем проход по всем записям юзера

        //Описание новости
        $text = $my_item->text;
        $text = str_replace('(^A-Za-zА-Яа-я0-9/!@#$%^&*()_+"|\}{[]:;.,)','',$text);
        $found = false;
        
        //найдем вхождение района в тексте
        foreach ($districts as $district){

            $dist = $district->getTitle();
            $dist = trim($dist);
            
            //проверим вхождение района
            if ($dist === mb_strtoupper("$dist",'Utf-8')){
                $temp_text = $text;

                $words_sao = strtok($temp_text, ' .,_(){}!@#$%:;+?');
                $dis_sim = false;

                while($words_sao !== FALSE){

                    if(strlen($words_sao) == strlen($dist)){
                       if(stristr($words_sao, $dist)){
                           $dis_sim = true;
                           break;

                       } //if
                    }//if

                     $words_sao = strtok(' .,_(){}!@#$%:;+?');
                }//while

            }
            else{
                //echo "Не аббревиатура <br/>";
                $dis_sim = $glob_service->IsSimilar($text,$dist);
            }
            if($dis_sim){
                break;
            }
        }//for districts

        if($dis_sim){

            foreach($stop_word_for_search as $sw){
                //поиск в тексте стоп-слова, если тру останавлеваем поиск, сохранаяем запись в базе
                $stop_word = trim( $sw->getWord() );
                if($glob_service->IsSimilar($text,$stop_word)==true){
                    $pos = true;
                    break;

                }//if
                else{
                    $pos = false;
                }
            }//foreach
            if ($pos != false){

            $date = date("d.m.o H:i",$my_item->date);
            //Заголовок
            $title = substr($text, 0, 50) . "...";
            $contains = false;

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
            $new_global_news->setSource("http://vk.com/id{$my_item->owner_id}?w=wall{$my_item->owner_id}_{$my_item->id}");
            $new_global_news->setDistrict($district->getId());
            $new_global_news->setDate($date);
            $new_global_news->setDistrict_str($district->getTitle());
            $new_global_news->setStop_words($sw->getWord());   
            $new_global_news->setSearchType('i');

            $glob_service->AddGlobalNews2($new_global_news);

            $new_global_news->setSearchType($user_vk_screen_name);
            $glob_service->AddToPersonOfInterest($new_global_news);

        }//if стоп-слова


        }//if Район подошел   
        else{
            $date = date("d.m.o H:i",$my_item->date);
            //Заголовок
            $title = substr($text, 0, 50) . "...";
            $contains = false;

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
            $new_global_news->setSource("http://vk.com/id{$my_item->owner_id}?w=wall{$my_item->owner_id}_{$my_item->id}");
            $new_global_news->setDistrict($district->getId());
            $new_global_news->setDate($date);
            $new_global_news->setDistrict_str('');
            $new_global_news->setStop_words('');   
            $new_global_news->setSearchType('i');


            $new_global_news->setSearchType($my_item->owner_id);
            $glob_service->AddToPersonOfInterest($new_global_news);              
        }

    }//foreach  резалт
        
    }
}