<head>
    <meta charset="Utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content" style="padding-top: 30px; text-align: justify">

<?php
 
ini_set("max_execution_time", "2500");
 
require_once './util/MySQL.php';
require_once './model/entity/global_news.php';
require_once './model/entity/stopword.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
require_once './twitter-api/TwitterAPIExchange.php';
require_once './util/Request.php';
require_once './model/entity/social_info.php';
require_once './model/entity/VkUser.php';

require_once './model/entity/bad_word.php';
use model\entity\bad_word;

use model\service\GlobalService;
use model\entity\global_news;
use util\Request;
use model\entity\stopword;
use model\entity\SocialInfo;
use model\entity\VkUser;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

$glob_service = new GlobalService();
$stop_word_for_search = $glob_service->GetStopWords();

//Получаем все районы из БД
$districts = $glob_service->GetDistricts();

//инициализация первого приложения


$url = 'https://api.twitter.com/1.1/search/tweets.json';
$request = new Request();
$i=1;
//$last_id = $glob_service->GetLastIdTwitter();

foreach ($districts as $district){

    if ($i<171){
        $settings = array(
           'oauth_access_token' => "3062725937-L6VtUnZ6xx644GWDU2Y3NHhz14yx1KADWeAnoxm",
           'oauth_access_token_secret' => "Q54JmVltQyKZjE5ymPAuCcWsipCOLo5GOfFWeUuLpdhqo",
           'consumer_key' => "lW5B5TUxOdwjKxVN9ufGEmYLy",
           'consumer_secret' => "BiJCp5uwPJ8bjufMzDbgRl4P7IzdhH0uawjr31hHHkhkdavYe4"
        );   

        $dist = $district->getTitle();
        $dist = trim($dist);
        $q_param = urlencode($dist);
        $count = count($districts);
        //&include_rts=false
        $getfield = "?q=$q_param&count=80";


        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);

        $fields = $twitter->setGetfield($getfield);
        $oAuth = $fields->buildOauth($url, $requestMethod);
        $response = $oAuth->performRequest();
        $js_obj = json_decode($response);

        if(@property_exists($js_obj, 'statuses')){

            foreach($js_obj->statuses as $status){

                $last_id = $status->id_str;
                $glob_service->SetLastIdTwitter($last_id);
                $text = $status->text;
                $found = false;
                
                

                echo "Район $dist<br/> $text <br/>";
  
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
                    echo "Не аббревиатура <br/>";
                    $dis_sim = $glob_service->IsSimilar($text,$dist);
                }
                
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
                        $text ='<a href="https://twitter.com/'.$screen_name.'" title="Ссылка на пользователя" target="_blank" class="tw_user_href">@' .$screen_name.'</a>: '.$text;
                        
                        $tw_users_table = $glob_service -> GetTwitterUser();                        
                        $srch_type = 't';
                        foreach($tw_users_table as $twitter_user){
                            $tw_user_sn = $twitter_user ->ScreenName;
                            if ($tw_user_sn == $screen_name){
                                $srch_type = 'i';
                                break;
                            }
                        }

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
                        $new_global_news->setSearchType($srch_type);
                        $description = $new_global_news->getDescription();


                        if(strlen($description) > 15){
                            $description = iconv_substr($description, 0, 15 , 'UTF-8');
                        }
                        else{
                            $description = iconv_substr($description, 0, 8 , 'UTF-8');
                        }

                        if($description != FALSE){

                            $isContains = $glob_service->IsContainsNews($description);
                                $glob_service->AddGlobalNews($new_global_news);
                            if(!$isContains){

                            }//if

                        }//if give to us sustr
                        echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";  
//                        if($result_insert == TRUE){
//                            echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";   
//                        }
//                        else{
//                            echo "<span class=\"bold\" style=\"background:red\">Результат добавления:</span><br/><pre>";   
//                            echo var_dump($new_global_news) . "</pre>";
//
//                        }

                    }//if стоп слово подошло

                }//if вход в проверку по стоп словам
                echo "<br/><br/>";
            }//foreach по ответу от апи
            echo '<br/><br/>';
        }
        //$i++;

    }//if перое приложение

    if($i>170){
        //инициализайия второго приложения
        $settings = array(
            'oauth_access_token' => "3062725937-Kw9iEiRVS8BdCoJs73qbrDoBtsdy8HWLe61P8b9",
            'oauth_access_token_secret' => "r3maoXVB4IW9KLymu0hgGPrNneoicA2AdThIUqH3Eyu4l",
            'consumer_key' => "tNw1sIS6Xa0F2InUcSVsUfsbl",
            'consumer_secret' => "DEceuesToCQGp64CHKJI4XHbWSzBjecjIQLdMAGukMJ6luhbnY"
        );

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $request = new Request();

        $dist = $district->getTitle();
        $dist = trim($dist);
        $q_param = urlencode($dist);
        $count = count($districts);
        //&include_rts=false
        $getfield = "?q=$q_param&count=80";


        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);

        $fields = $twitter->setGetfield($getfield);
        $oAuth = $fields->buildOauth($url, $requestMethod);
        $response = $oAuth->performRequest();
        $js_obj = json_decode($response);

        if(@property_exists($js_obj, 'statuses')){

            foreach($js_obj->statuses as $status){

                $last_id = $status->id_str;
                $glob_service->SetLastIdTwitter($last_id);
                $text = $status->text;
                $found = false;
                
                

                echo "Район $dist<br/> $text <br/>";
  
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
                    echo "Не аббревиатура <br/>";
                    $dis_sim = $glob_service->IsSimilar($text,$dist);
                }
                
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
                        
                        $tw_users_table = $glob_service -> GetTwitterUser();
                        //ScreenName
                        $srch_type = 't';
                        foreach($tw_users_table as $twitter_user){
                            $tw_user_sn = $twitter_user ->ScreenName;
                            if ($tw_user_sn == $screen_name){
                                $srch_type = 'i';
                                break;
                            }
                        }
                        
                        $user_image = $status->user->profile_image_url_https;
                        $created_at = $status->created_at;
                        $created_at = strtotime($created_at);

                        $created_at = date("d.m.o H:i",$created_at);

                        $source = "https://twitter.com/" . $status->user->id_str . "/status/" . $status->id_str;

                        $date = $status->created_at;
                        
                        $title = iconv_substr($text, 0,15, 'Utf-8').'...';
                        $text ='<a href="https://twitter.com/'.$screen_name.'" title="Ссылка на пользователя" target="_blank" class="tw_user_href">@' .$screen_name.'</a>: '.$text;

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
                        $new_global_news->setSearchType($srch_type);
                        $description = $new_global_news->getDescription();


                        if(strlen($description) > 15){
                            $description = iconv_substr($description, 0, 15 , 'UTF-8');
                        }
                        else{
                            $description = iconv_substr($description, 0, 8 , 'UTF-8');
                        }

                        if($description != FALSE){

                            $isContains = $glob_service->IsContainsNews($description);
                            $glob_service->AddGlobalNews($new_global_news);
//                            if(!$isContains){
//                                
//                            }//if

                        }//if give to us sustr
                        echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>"; 

                    }//if стоп слово подошло

                }//if вход в проверку по стоп словам
                echo "<br/><br/>";
            }//foreach по ответу от апи
            echo '<br/><br/>';
        }
        //$i++;

    }//if второе приложение
    $i++;
}//for

echo "final";
 ?>
    </div>
    </body>
