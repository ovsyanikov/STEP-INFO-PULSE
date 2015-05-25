<?php

require_once './model/entity/global_news.php';
require_once './model/entity/stopword.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
require_once './util/MySQL.php';
require_once './util/Request.php';

require_once './model/entity/bad_word.php';
use model\entity\bad_word;

use model\entity\global_news;
use model\entity\district;
use model\service\GlobalService;
use util\Request;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

$request = new Request();
$glob_service = new GlobalService();

$news = [];
    
$district = $request->getPostValue('District');
$stop_word = $request->getPostValue('STOP_W');

if($district == 'empty' && $stop_word == 'empty'){
    
    $result = $glob_service->GetGlobalNews(0,500);
    
    foreach ($result as $item){
        
        $source = $item -> getSource();
        $source = str_replace("'", "", $source);
        $source = str_replace("%20", "", $source);
        $item -> setSource($source);
    }    
    
    
    
    if(count($result) ==0){
        
        echo "end ";
        
    }//if
    else{
        echo json_encode($result);
    }
    
}
else if ($district != 'empty' && $stop_word == 'empty'){
    
    $district = trim($district);
    $d = $glob_service->GetDistrictByName($district);
    
    $news = $glob_service->GetGlobalNewsByDisrtict($d->getTitle());
    
    
    foreach ($news as $item){
        $source = $item -> getSource();
        $source = str_replace("'", "", $source);
        $source = str_replace("%20", "", $source);
        $item -> setSource($source);
    }    
     
    
    
    if(count($news) ==0){
        
        echo "end";
        
    }//if
    else{
        echo json_encode($news);
    }
    
}

else if ($district != 'empty' && $stop_word != 'empty'){
    
    $main_district = $glob_service->GetDistrictByName($district);
    $stop_word = trim($stop_word);
    $word = $glob_service->GetStopWordByTitle($stop_word);
    $new_news = $glob_service->GetGlobalNewsByStopWord($word->getWord(), $main_district->getId());
    foreach ($new_news as $item){
        $source = $item -> getSource();
        $source = str_replace("'", "", $source);
        $source = str_replace("%20", "", $source);
        $item -> setSource($source);
    } 
    if(count($new_news) == 0){
        
        echo "end";
        
    }//if
    else{
        echo json_encode($new_news);
    }
    
    
    
}
else if($district == 'empty' && $stop_word != 'empty'){
    
    $word = $glob_service->GetStopWordByTitle($stop_word);
    
    $new_news = $glob_service->GetGlobalNewsByStopWordWithoutDistrict($word->getWord());
    foreach ($new_news as $item){
        $source = $item -> getSource();
        $source = str_replace("'", "", $source);
        $source = str_replace("%20", "", $source);
        $item -> setSource($source);
    }     
     if(count($new_news) ==0){
        
        echo "end";
        
    }//if
    else{
        echo json_encode($new_news);
    }
}

