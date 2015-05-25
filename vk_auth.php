<?php
require_once './util/MySQL.php';
$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);
$stmt = \util\MySQL::$db->prepare("UPDATE vk_token SET vk_token = :vt, user_id = :uid");
 
$client_id = '4843223'; // ID приложения
$client_secret = 'q3eRLmVDMXKCx467CjUv'; // Защищённый ключ
$redirect_uri = 'http://user1187254.atservers.net/'; // Адрес сайта

$params = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri
);

$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

$stmt->bindParam(':vt',$token['access_token']);
$stmt->bindParam(':uid',$token['user_id']);
$res = $stmt->execute();
echo "$res";

