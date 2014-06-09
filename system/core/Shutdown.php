<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Shutdown{
    function __construct(){
        register_shutdown_function(array('System\Core\Shutdown', 'execute'), getcwd());
    }

    public static function execute($workingDir){
        chdir($workingDir);
        $errorCatch = error_get_last();
        if(isset($errorCatch['type'])){
            while(ob_get_status()['level']){
                Output::push('obStuck',ob_get_contents());
                ob_end_clean();
            }
            Errors::fatal($errorCatch);
        }
        if(\Config::get('environment') == 'development' && Errors::$catch) Errors::show();
        elseif(\Config::get('error_output') != '' && Errors::$catch){
            $path = explode('/', \Config::get('error_output'));
            $method = array_pop($path);
            $controller = implode('/', $path);
            Loader::$errorHandler = new $controller;
            Loader::$errorHandler->$method(Errors::$catch);
        }
        else{
            Output::flush();
        }
    }
}