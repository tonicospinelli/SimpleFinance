<?php

use Respect\Config\Container;

require __DIR__ . '/vendor/autoload.php';

// Project Constants
define('APP_ROOT', __DIR__);
define('APP_CONFIG_DIR', __DIR__ . '/config');
define('APP_WEB_DIR', __DIR__ . '/public');
define('SETUP_DIR', __DIR__ . '/setup');

$configFile = APP_CONFIG_DIR . '/config.ini.dist';
// Configuration file
if (is_file(APP_CONFIG_DIR . '/config.ini')) {
    $configFile = APP_CONFIG_DIR . '/config.ini';
}
$config = new Container($configFile);

// Set enviroment
$isDevMode = ($config->env !== 'dev') ? false : true;

if ($isDevMode) {
    ini_set('display_errors', 1);
    error_reporting(-1);
}

date_default_timezone_set($config->timezone);