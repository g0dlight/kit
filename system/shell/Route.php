<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

use System\Core\Router;

final class Route{
    public static function set($path, $action, $UrlParts=0){
        Router::$Routes[$path] = array($action, $UrlParts);
    }

    public static function block($controller, $method=''){
        Router::$Forbidden[$controller] = ($method)? (string)$method:'';
    }
}
require_once 'app/settings/Router.php';