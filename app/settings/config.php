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
| Error output
|--------------------------------------------------------------------------
|
| when the environment set to `production` by Default Kit stop when error occurred.
| Set here controller's method to override the default and run it when error occurred.
| Pattern: controller/method
| Example: welcome/index
|
*/
$config['error_output'] = '';

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
| For loading Kit instruments to the Primary class
|
*/
$config['instruments']['controllers'] = true;
$config['instruments']['models'] = true;
$config['instruments']['views'] = true;
$config['instruments']['helpers'] = true;
$config['instruments']['libraries'] = true;

##########################################################################################