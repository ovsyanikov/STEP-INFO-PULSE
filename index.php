<?php

use controller\FrontController;
function findClass($class) {
    $class = str_replace('\\', '/', $class) . '.php';
    if (file_exists($class)) {
        require_once "$class";
    }
}

spl_autoload_register('findClass');

GLOBAL $CONTROLLERS_NAMESPACE;
GLOBAL $DEFAULT_CONTROLLER;
GLOBAL $DEFAULT_ACTION;

$CONTROLLERS_NAMESPACE = 'controller';
$DEFAULT_CONTROLLER = 'start';
$DEFAULT_ACTION = 'welcome';
        
$fc = new FrontController();
$fc->start();
