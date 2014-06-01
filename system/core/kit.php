<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

spl_autoload_extensions('.php');
spl_autoload_register();

use System\Core\Loader;
use System\Core\Errors;
use System\Core\Output;
use System\Core\Router;

require_once 'system/core/Shutdown.php';

final class Kit{
    function __construct(){
        new Loader();
        new Errors();
        new Config();
        new Output();
        new Router();

        Router::getController();
        if(Router::$Controller == 'undefined') Errors::make('Default Controller not found (`'.Config::get('default_controller').'`)', true);
        else{
            if(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == '') Errors::make('Access Forbidden (`'.Router::$Controller.'`)', true);
            if(class_exists(Router::$Controller)){
                ob_start();
                Loader::$controller = new Router::$Controller();
                Output::push('constructor', ob_get_contents());
                ob_end_clean();
                Router::getMethod();
                if(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == Router::$Method) Errors::make('Access Forbidden (`'.Router::$Controller.'@'.Router::$Method.'`)', true);
                if(is_callable(array(Loader::$controller, Router::$Method))){
                    ob_start();
                    call_user_func_array(array(Loader::$controller, Router::$Method), Router::$UrlParts);
                    Output::push('method', ob_get_contents());
                    ob_end_clean();
                }
                else Errors::make('Method (`'.Router::$Method.'`) not found', true);
            }
            elseif(Router::$Controller) Errors::make('Controller not found (`'.Router::$Controller.'`) ', true);
        }
    }

    public static function dumpAutoLoad(){
        Loader::dumpAutoLoad();
    }

    public static function error($message, $fatal=false){
        Errors::make($message, $fatal);
    }
}