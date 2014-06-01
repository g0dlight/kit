<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core{

if(!defined('KIT_KEY')) exit('Access denied.');

    final class Router{
        public static $Routes = array();
        public static $Forbidden = array();
        public static $UrlParts = array();
        public static $Controller = 'undefined';
        public static $Method = 'undefined';

        function __construct(){
            $this->getUrlParts();
            $this->check();
        }

        public static function getController(){
            if(self::$Controller != 'undefined') return;
            $path = 'app/controllers/';
            $parts = false;
            foreach(self::$UrlParts as $value){
                if(file_exists($path.$value.'.php')){
                    self::$Controller = array_shift(self::$UrlParts);
                    return;
                }
                elseif(file_exists($path.$value.'/')){
                    $parts[] = array_shift(self::$UrlParts);
                    $path .= $value.'/';
                }
                else break;
            }
            if($parts){
                self::$UrlParts = array_merge($parts, self::$UrlParts);
                $path = 'app/controllers/';
            }
            if(file_exists($path.\Config::get('default_controller').'.php')){
                $fullPath = explode('/', \Config::get('default_controller'));
                self::$Controller = array_pop($fullPath);
            }
        }

        public static function getMethod(){
            if(self::$Method != 'undefined') return;
            self::$Method = 'index';
            if(isset(self::$UrlParts[0]) && method_exists(Loader::$controller, self::$UrlParts[0])){
                if(substr(self::$UrlParts[0], 0, 2) != '__') self::$Method = array_shift(self::$UrlParts);
            }
        }

        private function check(){
            if(isset(self::$Routes[self::$UrlParts[0]])){
                self::$Controller = false;
                self::$Method = false;
                $route = array_shift(self::$UrlParts);
                $route = self::$Routes[$route];
                if(is_callable($route)){
                    ob_start();
                    $route = call_user_func_array($route, self::$UrlParts);
                    Output::push('constructor', ob_get_contents());
                    ob_end_clean();
                }
                if(is_string($route)){
                    $route = explode('@', $route);
                    switch(count($route)){
                        case 1:
                            self::$Controller = $route[0];
                            self::$Method = 'undefined';
                            break;
                        case 2:
                            self::$Controller = $route[0];
                            self::$Method = $route[1];
                            break;
                    }
                }
            }
        }

        private function getUrlParts(){
            $accessPath = (isset($_SERVER['PATH_INFO']))? $_SERVER['PATH_INFO']:'/';
            $accessPath = explode('/', $accessPath);
            array_shift($accessPath);
            if($accessPath[0] == NULL) array_shift($accessPath);
            if(!count($accessPath)) self::$UrlParts = array('');
            else self::$UrlParts = $accessPath;
            return true;
        }
    }
}
namespace {

    use System\Core\Router;

    final class Route{
        public static function set($path, $action){
            Router::$Routes[$path] = $action;
        }

        public static function block($controller, $method=''){
            Router::$Forbidden[$controller] = ($method)? (string)$method:'';
        }
    }
    require_once 'app/settings/Router.php';
}