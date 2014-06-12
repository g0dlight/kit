<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

spl_autoload_extensions('.php');
spl_autoload_register();

use System\Core\Loader;
use System\Core\Errors;
use System\Core\Shutdown;
use System\Core\Router;
use System\Core\Output;

final class Kit{
    function __construct(){
        new Loader();
        new Config();
        new Errors();
        new Shutdown();
        new Log();
        new Router();

        Router::getController();
        if(isset(Router::$Controller['undefined'])) throw new KitException('Controller (`'.Router::$Controller['undefined'].'`) not found');
        elseif(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == '') throw new KitException('Access Forbidden (`'.Router::$Controller.'`)');
        elseif(class_exists(Router::$Controller)){
            ob_start();
            Loader::$controller = new Router::$Controller();
            Output::push('constructor', ob_get_contents());
            ob_end_clean();
            Router::getMethod();
            if(isset(Router::$Method['undefined'])) throw new KitException('Method (`'.Router::$Controller.'@'.Router::$Method['undefined'].'`) not found');
            elseif(isset(Router::$Forbidden[Router::$Controller]) && Router::$Forbidden[Router::$Controller] == Router::$Method) throw new KitException('Access Forbidden (`'.Router::$Controller.'@'.Router::$Method.'`)');
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
}