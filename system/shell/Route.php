<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

use System\Core\Router;

final class Route{
    function __construct(){
        require_once 'app/settings/Router.php';
    }

    public static function set($path, $action, $UrlParts=0){
        Router::$Routes[$path] = array($action, $UrlParts);
    }

    public static function demandRequest($requestType, $callBack=''){
        if(strtolower($requestType) != strtolower($_SERVER['REQUEST_METHOD'])){
            if(is_callable($callBack)) $callBack();
            else throw new KitException('Access Forbidden, demand '.$requestType.' request');
        }
    }

    public static function block($controller, $method=''){
        Router::$Forbidden[$controller] = ($method)? (string)$method:'';
    }
}