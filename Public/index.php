<?php

define('BASE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);

require_once BASE_PATH.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Kit\Core\System;

if(isset($argv))
	System::setArgv($argv);

$app = new System();

$app->run();

echo "\n";
