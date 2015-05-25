<?php

require_once './model/service/GlobalService.php';
require_once './model/entity/CronProperties.php';
require_once './util/MySQL.php';

use model\service\GlobalService;
use model\entity\CronProperties;
\util\MySQL::$db = new \PDO('mysql:host=localhost;dbname=user1187254_u304199710_info', 'u304199710_alex', '1qaz2wsx');

$global = new GlobalService();

$cron = $global->IsCronEnable();

echo "$cron";

