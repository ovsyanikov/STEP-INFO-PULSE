<?php
require_once './util/MySQL.php';
require_once './model/service/GlobalService.php';

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);
//$stmt = \util\MySQL::$db->prepare("UPDATE vk_token SET vk_token = :vt, user_id = :uid");
 
 use model\service\GlobalService;
 
 
$g_service = new GlobalService();

$json_tw_auth_answer = $g_service->get_twitter_profile_info();
echo json_encode($json_tw_auth_answer);

//echo json_encode($json_tw_auth_answer);