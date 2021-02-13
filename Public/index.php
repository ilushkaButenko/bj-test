<?php

use iButenko\App\Boot\Autoloader;
use iButenko\App\App;

// Short name for directory separator
define('DS', DIRECTORY_SEPARATOR);

// Path to root directory
define('ROOT_DIR', dirname(__DIR__));

// Load app
require(ROOT_DIR.DS.'App'.DS.'Boot'.DS.'Autoloader.php');
Autoloader::autoload();

// Run app
$app = new App();
$app->run();
