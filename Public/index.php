<?php

define('BASE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);

require_once BASE_PATH.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$app = new Kit\Core\System();

echo $app->run();
