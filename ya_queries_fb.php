<head>
    <meta charset="Utf-8">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="content" style="padding-top: 30px; text-align: justify">
<?php

ini_set("max_execution_time", "2500");
 //error_reporting(E_ERROR );
require_once './util/MySQL.php';
require_once './model/entity/global_news.php';
require_once './model/entity/stopword.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
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
$result_items = [];    
$i = 1;
$d_title_tosearch;
$d_count = count($districts);
$last_step = $d_count%10;

foreach ($districts as $district){//Проходим по всем районам
    
    $d_title = $district->getTitle();
    $d_title_tosearch .= '|'.$d_title;
    if($i%5 == 0 || $d_count-$i<=$last_step){
        
//        
    $d_title_tosearch = trim($d_title_tosearch);
    $d_title_tosearch = iconv_substr($d_title_tosearch, 1, iconv_strlen($d_title_tosearch, 'Utf-8')-1, 'Utf-8');
    $d_title_five = $d_title_tosearch;
    $d_title_tosearch = str_replace(' ', '+', $d_title_tosearch);
    

    echo "<h2>$i РАЙОНЫ к поиску - $d_title_tosearch<br></h2>" ;
    $d_title_tosearch = urlencode($d_title_tosearch);
    
    //&server=facebook.com&numdoc=8&ft=all
    $result = file_get_contents("https://blogs.yandex.ru/search.rss?text=$d_title_tosearch&server=facebook.com&numdoc=99&ft=all");       
//    echo "<pre>" ;
//    echo "$result";
//    echo "</pre>" ; 
//    die();   
    
    $items = new SimpleXMLElement($result);

    
    foreach (@$items->channel->item as $yandex_item){
        $result_items[] = $yandex_item;
    }//foreach

    
    foreach ($result_items as $my_item){

        $pos = false;
        //Описание новости
        $text = $my_item->description;
        
        $text = str_replace('(^A-Za-zА-Яа-я0-9/!@#$%^&*()<>_+"|\}{[]:;.,)','',$text);
        $found = false;

        echo "$i Районы $d_title_five<br/> $text <br/>";
        $distr_final;
        $d_title_five_arr = explode('|', $d_title_five);
        //проход по пяти районам
        foreach ($d_title_five_arr as $d_title_of_five){
        
            //проверим вхождение района
            if ($d_title_of_five === mb_strtoupper("$d_title_of_five",'Utf-8')){
                $temp_text = $text;

                $words_sao = strtok($temp_text, ' .,_(){}!@#$%:;+?<>');
                $dis_sim = false;

                while($words_sao !== FALSE){

                    if(strlen($words_sao) == strlen($d_title_of_five)){
                        $words_sao = mb_strtolower("$words_sao",'Utf-8');
                        $d_title_of_five = mb_strtolower("$d_title_of_five",'Utf-8');
                        
                        if(stristr($words_sao, $d_title_of_five) ){
                           $dis_sim = true;
                           break;

                       } //if
                    }//if

                     $words_sao = strtok(' .,_(){}!@#$%:;+?<>');
                }//while

            }
            else{
                //echo "Не аббревиатура <br/>";
                $dis_sim = $glob_service->IsSimilar($text,$d_title_of_five);
                
            }
            if($dis_sim){
                $distr_final = $d_title_of_five;
                break;
            }
        
        }
        //echo 'Текст:'.$text.'<br/>';
        //echo '<span class="bold">'.$distr_final.'</span>';
        
        
        
        //новый поиск по словам
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
                
                $distr_obj = $glob_service->GetDistrictByName($distr_final);
                $date = $my_item->pubDate;
                $date = strtotime($date);
                $date = date('d.m.o H:i',$date);
                //Заголовок
                $title = $my_item->title;

                
                $srch_type = 'f';
                $new_global_news = new global_news();
                $new_global_news->setTitle($title.'...');
                $new_global_news->setDescription($text);
                $new_global_news->setImage(NULL);
                $new_global_news->setSource($my_item->link);
                $new_global_news->setDistrict($distr_obj->getId());
                $new_global_news->setDate($date);
                $new_global_news->setDistrict_str($distr_obj->getTitle());
                $new_global_news->setStop_words($sw->getWord());
                $new_global_news->setSearchType($srch_type);

                $glob_service->AddGlobalNews($new_global_news);
                echo "<span class=\"bold\" style=\"background:red\">добавлено</span><br/>";


            }//if стоп-слова
        }//if район подошел
        echo "<br/><br/>";
    }//foreach
    //die();
    $d_title_tosearch = '';
    }//if каждый 5ый
//    else if($d_count-$i<=$last_step){
//        echo "<br/><br/>$i<br/>";
//        $new_distr = array_slice($districts,$i-1,$last_step);
//        echo "<pre>";
//         print_r($new_distr);
//        echo "</pre>";
//        break;
//    }
    $i++;
    
}//foreach

echo "final";
 ?>
    </div>
    </body>
