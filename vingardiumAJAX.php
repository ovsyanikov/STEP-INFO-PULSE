<?php

function findClass($class) {
    $class = str_replace('\\', '/', $class) . '.php';
    if (file_exists($class)) {
        require_once "$class";
    }
}

spl_autoload_register('findClass');

use model\service\GlobalService;
use util\Request;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

\util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);

class VingardiumAjax{
    
    public static function RemoveNewsByIdAction($id){
        $newsID = intval($id);
        $glob_service = new GlobalService();
        $glob_service->DeleteGlobalNewsById($newsID);
        return true;
        
    }
    
     public static function GetNewsByIdAction($id){
        $newsID = intval($id);
        $glob_service = new GlobalService();
        return $glob_service->GetGlobalNewsById($newsID);
        
    }
    
}
$r = new Request();

$method = $r->getPostValue('method');
$params =  $_POST['params'];

if(!empty($params)){
   
   if(count($params) == 1){
       $res =  VingardiumAjax::{"{$method}Action"}($params[0]);
   }
   else{
       echo "else";
   }
   
   echo json_encode($res);
}
else{
    
    $res =  VingardiumAjax::{"{$method}Action"}();
   
   if($res){
       echo json_encode($res);
   }
   else{
       echo $params;
       
   }
}