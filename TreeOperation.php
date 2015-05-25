<?php

require_once './util/Request.php';
require_once './util/MySQL.php';
require_once './model/service/GlobalService.php';
require_once './model/entity/district.php';
require_once './model/entity/DistrictTree.php';

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);
 
use model\entity\district;
use model\service\GlobalService;
use model\entity\DistrictTree;

use util\Request;

$global = new GlobalService();
$r = new Request();

if($_POST['CLEAR_TABLE']){
    
   $global->ClearDistrictTree();
    
}
else if($_POST['ADD_CHILD']){
    
    $parrent = $r->getPostValue('parrent');
    $child = $r->getPostValue('child');
    
    $district_parrent = $global->GetDistrictByName(trim($parrent));
    //echo "parrent district - " . $district_parrent->getId();
    
    $district_child = $global->GetDistrictByName(trim($child));
    //echo "Child district - " . $district_child->getId();
    
    if($district_child == NULL){
        $global->AddDistrictParrendAndChild($district_parrent->getId(),NULL);
    }
    else{
        $global->AddDistrictParrendAndChild($district_parrent->getId(),$district_child->getId());
    }
    
}
else if($_POST['GET_DISTRICTS_TREE']){
    
   $tree = $global->GetDistrictTree();
   echo json_encode($tree);
   
}
else if($_POST['GET_DISTRICT_CHILD']){
    
   $DIS_ID = $r->getPostValue('d_id');
   
   $tree = $global->GetAllChild($DIS_ID);
   
   if(count($tree) == 0){
       echo "no";
   }//if
   else{
       echo json_encode($tree);
   }//else
   
   
}