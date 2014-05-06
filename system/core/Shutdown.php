<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

use System\Core\Errors;
use System\Core\Output;

function KitShutdown($workingDir){
    chdir($workingDir);
    $errorCatch = error_get_last();
    if(isset($errorCatch['type'])){
        while(ob_get_status()['level']){
            ob_end_clean();
        }
        Errors::fatal($errorCatch);
    }
    // catch fatal error and report to Error class
    if(Kit::$Config['environment'] == 'development' && Errors::$catch) Errors::show();
    else{
        Output::flush();
    }
}

register_shutdown_function('KitShutdown', getcwd());