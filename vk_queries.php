<head>
    <meta charset="Utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content" style="padding-top: 30px; text-align: justify">

<?php

ini_set("max_execution_time", "1200");
 
require_once './util/MySQL.php';
require_once './model/entity/global_news.php';
require_once './model/entity/stopword.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
require_once './twitter-api/TwitterAPIExchange.php';
require_once './util/Request.php';
require_once './model/entity/social_info.php';

require_once './model/entity/bad_word.php';
use model\entity\bad_word;

use model\service\GlobalService;
use model\entity\global_news;

use util\Request;
use model\entity\stopword;
use model\entity\SocialInfo;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

$glob_service = new GlobalService();
$stop_word_for_search = $glob_service->GetStopWords();

//Получаем все районы из БД
$districts = $glob_service->GetDistricts();
$first_time = time() - 3600;


foreach ($districts as $district){//Проходим по всем районам
    

    $d_title = $district->getTitle();
    $d_title = trim($d_title);
    $to_search = urlencode($d_title);

    $result = file_get_contents("https://api.vk.com/method/newsfeed.search?q=$to_search&extended=0&start_time=$first_time&count=195&v=5.28");

    $result_from_json = json_decode($result);

    foreach ($result_from_json->response->items as $my_item){

        $pos = false;
        //Описание новости
        $text = $my_item->text;
        
        $text = str_replace('(^A-Za-zА-Яа-я0-9/!@#$%^&*()_+"|\}{[]:;.,)','',$text);
        $found = false;

        
        echo "Район $d_title<br/> $text <br/>";
  
        //проверим вхождение района
        if ($d_title === mb_strtoupper("$d_title",'Utf-8')){
            $temp_text = $text;
            
            $words_sao = strtok($temp_text, ' .,_(){}!@#$%:;+?');
            $dis_sim = false;
            
            while($words_sao !== FALSE){
                
                if(strlen($words_sao) == strlen($d_title)){
                   if(stristr($words_sao, $d_title) ){
                       $dis_sim = true;
                       break;
                       
                   } //if
                }//if
                
                 $words_sao = strtok(' .,_(){}!@#$%:;+?');
            }//while
            
        }
        else{
            echo "Не аббревиатура <br/>";
            $dis_sim = $glob_service->IsSimilar($text,$d_title);
        }
        
        
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
            }//foreach

            if ($pos == true){

            $date = date("d.m.o H:i",$my_item->date);
            //Заголовок

            $title = iconv_substr($text, 0, 50 , 'UTF-8') . "...";

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
            $title = str_ireplace('\n', '', $title);
            $new_global_news = new global_news();
            $new_global_news->setTitle($title);
            $new_global_news->setDescription($text);
            $new_global_news->setImage($img);
            $new_global_news->setSource("http://vk.com/feed?w=wall{$my_item->owner_id}_{$my_item->id}");
            $new_global_news->setDistrict($district->getId());
            $new_global_news->setDate($date);
            $new_global_news->setDistrict_str($district->getTitle());
            $new_global_news->setStop_words($sw->getWord());   
            $new_global_news->setSearchType('v');

            $result_insert = $glob_service->AddGlobalNews2($new_global_news);
            echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";
//            if($result_insert == TRUE){
//                echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";   
//            }
//            else{
//                echo "<span class=\"bold\" style=\"background:red\">Результат добавления:</span><br/><pre>";   
//                echo var_dump($new_global_news) . "</pre>";
//
//            }
        }//if стоп-слова 

        }//if район входит в текст
        
        
        echo "<br/><br/>";
    }//foreach
    echo '<br/><br/>';
}//foreach

echo "final";

 ?>
    </div>
    </body>
