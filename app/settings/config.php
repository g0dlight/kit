<?php
$config = array();
##########################################################################################

/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
|
| Set the environment to -> development || production;
| By Default Kit set it to -> development;
|
*/
$config['environment'] = 'development';

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
|
| Set the default controller
|
*/
$config['default_controller'] = 'welcome';

/*
|--------------------------------------------------------------------------
| Load Kit instrument
|--------------------------------------------------------------------------
|
| Set -> true || false;
| For loading Kit instruments to the controllers
|
*/
$config['instruments']['models'] = true;
$config['instruments']['views'] = true;
$config['instruments']['helpers'] = true;
$config['instruments']['libraries'] = true;

##########################################################################################