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
            $this->checkRoute();
        }

        public static function getController(){
            if(!self::$Controller) return;
            if(self::$Controller != 'undefined'){
                $controller = self::$Controller;
                self::$Controller = 'undefined';
            }
            elseif(!empty(self::$UrlParts[0]) && $value = self::checkUrl()){
                self::$Controller = $value;
                return;
            }
            else $controller = \Config::get('default_controller');

            if(Loader::get($controller)){
                self::$Controller = $controller;
                return;
            }
            self::$Controller = array('undefined'=>$controller);
        }

        private static function checkUrl(){
            $count = 0;
            do{
                $count++;
                foreach(self::$UrlParts as $urlNum => $urlPart){
                    if(isset(Loader::$autoLoad[$urlPart])){
                        $pathFile = explode('/',Loader::$autoLoad[$urlPart]);
                        $pathUrl = array_slice(self::$UrlParts, 0, $urlNum);
                        array_unshift($pathUrl, 'app','controllers');
                        if(implode('/',$pathFile) == implode('/',$pathUrl)){
                            self::$UrlParts = array_slice(self::$UrlParts, $urlNum+1);
                            return $urlPart;
                        }
                    }
                }
                if(\Config::get('environment') == 'development') Loader::dumpAutoLoad();
                else return;
            }
            while($count < 2);
            return false;
        }

        public static function getMethod(){
            if(!self::$Method) return;
            $methods = array();
            if(self::$Method != 'undefined'){
                $methods['router'] = self::$Method;
                self::$Method = 'undefined';
            }
            else{
                if(!empty(self::$UrlParts[0])) $methods['url'] = self::$UrlParts[0];
                $methods['default'] = 'index';
            }
            foreach($methods as $key => $method){
                if(method_exists(Loader::$controller, $method)){
                    if($key == 'url') array_shift(self::$UrlParts);
                    if(!is_callable(array(Loader::$controller, $method)) || substr($method, 0, 2) == '__'){
                        Router::$Forbidden[self::$Controller] = $method;
                    }
                    self::$Method = $method;
                    return;
                }
                self::$Method = array('undefined'=>$method);
            }
        }

        private function checkRoute(){
            if(empty(self::$UrlParts[0])) return;
            if(isset(self::$Routes[self::$UrlParts[0]])){
                self::$Controller = false;
                self::$Method = false;
                $routeName = array_shift(self::$UrlParts);
                $route = self::$Routes[$routeName][0];
                if(is_callable($route)){
                    ob_start();
                    $UrlParts = (int)self::$Routes[$routeName][1];
                    $functionVars = array();
                    if($UrlParts > 0){
                        while($UrlParts > 0){
                            $functionVars[] = array_shift(self::$UrlParts);
                            $UrlParts--;
                        }
                    }
                    $route = call_user_func_array($route, $functionVars);
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
            if(!count($accessPath)) self::$UrlParts = array();
            else self::$UrlParts = $accessPath;
            return true;
        }
    }
}
namespace {

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
}