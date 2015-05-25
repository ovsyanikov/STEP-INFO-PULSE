<meta charset="UTF-8">
<?php 

ini_set("max_execution_time", "2500");

function findClass($class) {
    $class = str_replace('\\', '/', $class) . '.php';
    if (file_exists($class)) {
        require_once "$class";
    }
}

spl_autoload_register('findClass');

use model\service\InfoPulseService;
use model\service\SocialService;
use model\service\GlobalService;
use model\entity\InfoPulseUser;
use model\entity\SocialType;
use model\entity\global_news;
use model\service\TwitterAPIExchange;

$db_name = \util\MySQL::GetDbName();
$db_user = \util\MySQL::GetUserName();
$db_user_password = \util\MySQL::GetUserPassword();

 \util\MySQL::$db = new \PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_user_password);
 
 $social_service = new SocialService();
 
 $infopulse_service = new InfoPulseService();
 
 $socials = $infopulse_service->GetSocialTypes();
 
foreach ($socials as $social) { 
    
    $social_service->{"GetWall{$social->SocialName}"}();
    
}//foreach
 
 


