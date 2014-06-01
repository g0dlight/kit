<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

spl_autoload_extensions('.php');
spl_autoload_register();

use System\Core\Loader;
use System\Core\Errors;
use System\Core\Output;

require_once 'system/core/Shutdown.php';



final class Kit{
    static public $Router = false;
    static public $UrlParts = array();
    static public $Controller = array('Stage'=>'none','Path'=>'app/controllers/','Name'=>'undefined','Method'=>'index');

    function __construct(){
        new Errors();
        new Loader();
        new Config();
        new Output();

        $this->getSettings();
        $this->getUrlParts();
        $this->getController();

        if(self::$Controller['Name'] == 'undefined') Errors::make('Default Controller not found (`'.Config::get('default_controller').'`)', true);
        elseif(isset(self::$Router['error'])) Errors::make('Controller not found (`'.self::$Controller['Path'].self::$Controller['Name'].'`) Router `'.self::$Router['error'].'`', true);
        else{
            require_once self::$Controller['Path'].self::$Controller['Name'].'.php'.'';
            self::$Controller['Stage'] = 'file loaded';
            if(class_exists(self::$Controller['Name'], false)){
                ob_start();
                Loader::$controller = new self::$Controller['Name']();
                Output::push('constructor', ob_get_contents());
                ob_end_clean();
                self::$Controller['Stage'] = 'class loaded';
                if(isset(self::$UrlParts[0]) && method_exists(Loader::$controller, self::$UrlParts[0])){
                    self::$Controller['Method'] = array_shift(self::$UrlParts);
                }
                if(is_callable(array(Loader::$controller, self::$Controller['Method']))){
                    ob_start();
                    call_user_func_array(array(Loader::$controller, self::$Controller['Method']), self::$UrlParts);
                    Output::push('method', ob_get_contents());
                    ob_end_clean();
                    self::$Controller['Stage'] = 'method loaded';
                }
                else Errors::make('Method (`'.self::$Controller['Method'].'`) not found', true);
            }
        }
    }

    static public function dumpAutoLoad(){
        Loader::dumpAutoLoad();
    }

    private function getSettings(){
        $router = false;
        require_once 'app/settings/Router.php';
        self::$Router = $router;
    }
    // Get and check the default settings for `Config` && `Router` && `AutoLoad`.

    private function getUrlParts(){
        $accessPath = (isset($_SERVER['PATH_INFO']))? $_SERVER['PATH_INFO']:'/';
        $accessPath = explode('/', $accessPath);
        array_shift($accessPath);
        if($accessPath[0] == NULL) array_shift($accessPath);
        if(!count($accessPath)) return array();
        self::$UrlParts = $accessPath;
        return true;
    }
    // Divider PATH_INFO to array by `/` into $this->_UrlParts.

    private function getController(){
        $path = self::$Controller['Path'];
        if(self::$UrlParts){
            if(isset(self::$Router[self::$UrlParts[0]])){
                $fullPath = explode('/', self::$Router[self::$UrlParts[0]]);
                self::$Router = array_shift(self::$UrlParts);
                self::$Controller['Method'] = array_pop($fullPath);
                self::$Controller['Name'] = array_pop($fullPath);
                self::$Controller['Path'] = $path.implode('/', $fullPath).'/';
                if(!file_exists(self::$Controller['Path'].self::$Controller['Name'].'.php')) self::$Router = array('error'=>self::$Router);
                return;
            }
            $parts = false;
            foreach(self::$UrlParts as $value){
                if(file_exists($path.$value.'.php')){
                    array_shift(self::$UrlParts);
                    self::$Controller['Path'] = $path;
                    self::$Controller['Name'] = $value;
                    return;
                }
                elseif(file_exists($path.$value.'/')){
                    $parts[] = array_shift(self::$UrlParts);
                    $path .= $value.'/';
                }
                else break;
            }
            if($parts) self::$UrlParts = array_merge($parts, self::$UrlParts);
        }
        if(file_exists($path.Config::get('default_controller').'.php')){
            $fullPath = explode('/', Config::get('default_controller'));
            self::$Controller['Name'] = array_pop($fullPath);
            self::$Controller['Path'] = $path.implode('/', $fullPath).'/';
        }
    }
    // Get the controller
}