<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

spl_autoload_extensions('.php');
spl_autoload_register();

use System\Core\Loader;
use System\Core\Shutdown;
use System\Core\Errors;
use System\Core\Output;
use System\Core\Router;

final class Kit{
    function __construct(){
        new Loader();
        new Config();
        new Shutdown();
        new Errors();
        new Output();
        new Router();

        Router::getController();
        if(isset(Router::$Controller['undefined'])) Errors::make('Controller (`'.Router::$Controller['undefined'].'`) not found', true);
        elseif(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == '') Errors::make('Access Forbidden (`'.Router::$Controller.'`)', true);
        elseif(class_exists(Router::$Controller)){
            ob_start();
            Loader::$controller = new Router::$Controller();
            Output::push('constructor', ob_get_contents());
            ob_end_clean();
            Router::getMethod();
            if(isset(Router::$Method['undefined'])) Errors::make('Method (`'.Router::$Controller.'@'.Router::$Method['undefined'].'`) not found', true);
            elseif(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == Router::$Method) Errors::make('Access Forbidden (`'.Router::$Controller.'@'.Router::$Method.'`)', true);
            else{
                ob_start();
                call_user_func_array(array(Loader::$controller, Router::$Method), Router::$UrlParts);
                Output::push('method', ob_get_contents());
                ob_end_clean();
            }
        }
    }

    public static function dumpAutoLoad(){
        Loader::dumpAutoLoad();
    }

    public static function error($message, $fatal=false){
        Errors::make($message, $fatal);
    }
}