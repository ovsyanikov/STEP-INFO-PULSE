<head>
    <meta charset="Utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content" style="padding-top: 30px; text-align: justify">
<?php

ini_set("max_execution_time", "2500");
 error_reporting(E_ERROR );
require_once './util/MySQL.php';
require_once './model/entity/global_news.php';
require_once './model/entity/stopword.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
require_once './util/Request.php';
require_once './model/entity/VKGroups.php';

require_once './model/entity/bad_word.php';
use model\entity\bad_word;

use model\service\GlobalService;
use model\entity\global_news;
use util\Request;
use model\entity\stopword;
use model\entity\VKGroups;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

 

 
$glob_service = new GlobalService();
$tk = $glob_service-> GetAccessToken();
$vk_groups = $glob_service->GetVkGroups();
$stop_word_for_search = $glob_service->GetStopWords();

//Получаем все районы из БД
$districts = $glob_service->GetDistricts();
//$first_time = time() - 14400;
$first_time = time() - 3600;

//цикл по группам
foreach ($vk_groups as $one_group){
    
$tk = $glob_service-> GetAccessToken();
//цикл по группам
$i = 1;


$owner_id = $one_group->GroupTitleId;
echo "<br/>owner id = $owner_id<br/>" ;

$result = file_get_contents("https://api.vk.com/method/wall.get?access_token=$tk&domain=$owner_id&count=100&v=5.29");
$result_from_json = json_decode($result);


if(count($result_from_json->response->items) ==0 ){
    echo "Запрос к VK-API \"$d_title\" вернул пустой результат<br/><br/>";
}
     
    
foreach ($result_from_json->response->items as $my_item){

    //начинаем проход по всем записям юзера

    //Описание новости
    $text = $my_item->text;
    $text = str_replace('(^A-Za-zА-Яа-я0-9/!@#$%^&*()_+"|\}{[]:;.,)','',$text);
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
        if ($pos != false){

        $date = date("d.m.o H:i",$my_item->date);
        //Заголовок
        $title = iconv_substr($text, 0, 50, 'UTF-8') . "...";
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
//        
//        $owner = $my_item->owner_id;
//        $owner = iconv_substr($owner, 1, iconv_strlen($owner,'Utf-8'), 'Utf-8');
//        
        $new_global_news->setSource("http://vk.com/{$owner_id}?w=wall{$my_item->owner_id}_{$my_item->id}");        
        $new_global_news->setDistrict($district->getId());
        $new_global_news->setDate($date);
        $new_global_news->setDistrict_str($district->getTitle());
        $new_global_news->setStop_words($sw->getWord());   
        $new_global_news->setSearchType('i');

        $glob_service->AddGlobalNews2($new_global_news);

        $new_global_news->setSearchType($owner_id);
        $glob_service->AddToPersonOfInterest($new_global_news);
        echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";
        //}//if


    }//if стоп-слова


    }//if Район подошел   
//    else{
//        $date = date("d.m.o H:i",$my_item->date);
//        //Заголовок
//        $title = substr($text, 0, 50) . "...";
//        $contains = false;
//
//        $img = NULL;
//
//        if(property_exists($my_item, 'attachments')){
//            $att = $my_item->attachments[0];
//
//            if(property_exists($att,'photo')){
//                $photo = $my_item->attachments[0]->photo;
//                if(property_exists($photo,'photo_1280')){
//                    $img = $my_item->attachments[0]->photo->photo_1280;
//                }//if
//                else if(property_exists($photo,'photo_604')){
//                    $img = $my_item->attachments[0]->photo->photo_604;
//                }
//            }//if
//        }//if
//
//        $new_global_news = new global_news();
//        $new_global_news->setTitle($title);
//        $new_global_news->setDescription($text);
//        $new_global_news->setImage($img);
//        $owner = $my_item->owner_id;
//        $owner = iconv_substr($owner, 1, iconv_strlen($owner,'Utf-8'), 'Utf-8');
//        
//        $new_global_news->setSource("http://vk.com/id{$owner}?w=wall{$my_item->owner_id}_{$my_item->id}");
//        $new_global_news->setDistrict($district->getId());
//        $new_global_news->setDate($date);
//        $new_global_news->setDistrict_str('');
//        $new_global_news->setStop_words('');   
//        $new_global_news->setSearchType('i');
//
//        //$glob_service->AddGlobalNews2($new_global_news);
//
//        $new_global_news->setSearchType($owner_id);
//        $glob_service->AddToPersonOfInterest($new_global_news);
//        echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";                
//    }
    echo "<br /><br />";
}//foreach  резалт

$i++;

}//forech   группы
echo "final";

 ?>
    </div>
    </body>
