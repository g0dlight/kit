<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

spl_autoload_extensions('.php');
spl_autoload_register();

require_once 'system/core/Shutdown.php';

use System\Core\Errors;
use System\Core\Output;
use System\Core\Loader;

final class Kit{
    static public $Config = false;
    static public $AutoLoad = false;
    static public $Router = false;
    static public $UrlParts = array();
    static public $Controller = array('Stage'=>'none','Path'=>'app/controllers/','Name'=>'undefined','Method'=>'index');

    function __construct(){
        $this->getSettings();
        $this->getUrlParts();
        $this->getController();

        new Errors();
        new Output();
        new Loader();

        if(require_once self::$Controller['Path'].self::$Controller['Name'].'.php'.''){
            self::$Controller['Stage'] = 'file loaded';
            if(class_exists(self::$Controller['Name'], false)){
                ob_start();
                $Controller = new self::$Controller['Name']();
                Output::push('constructor', ob_get_contents());
                ob_end_clean();
                self::$Controller['Stage'] = 'class loaded';
                if(isset(self::$UrlParts[0]) && method_exists($Controller, self::$UrlParts[0])){
                    self::$Controller['Method'] = array_shift(self::$UrlParts);
                }
                if(is_callable(array($Controller,self::$Controller['Method']))){
                    ob_start();
                    call_user_func_array(array($Controller, self::$Controller['Method']), self::$UrlParts);
                    Output::push('method', ob_get_contents());
                    ob_end_clean();
                    self::$Controller['Stage'] = 'method loaded';
                }
            }
        }
    }

    private function getSettings(){
        $config = false;
        $router = false;
        $autoLoad = false;

        require_once 'app/settings/Config.php';
        require_once 'app/settings/Router.php';
        require_once 'app/settings/AutoLoad.php';

        ############
        ## Config ##
        ############
        self::$Config = $config;

        ## environment
        if(!isset(self::$Config['environment']) || self::$Config['environment'] != 'production')
            self::$Config['environment'] = 'development';

        ## default controller
        if(empty(self::$Config['default_controller']))
            self::$Config['default_controller'] = 'undefined';

        ## instruments
        if(!isset(self::$Config['instruments']['models']) || self::$Config['instruments']['models'] !== false)
            self::$Config['instruments']['models'] = true;

        if(!isset(self::$Config['instruments']['views']) || self::$Config['instruments']['views'] !== false)
            self::$Config['instruments']['views'] = true;

        if(!isset(self::$Config['instruments']['helpers']) || self::$Config['instruments']['helpers'] !== false)
            self::$Config['instruments']['helpers'] = true;

        if(!isset(self::$Config['instruments']['libraries']) || self::$Config['instruments']['libraries'] !== false)
            self::$Config['instruments']['libraries'] = true;

        ############
        ## Router ##
        ############
        self::$Router = $router;

        ##############
        ## AutoLoad ##
        ##############
        self::$AutoLoad = $autoLoad;
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
        self::$Controller['Name'] = self::$Config['default_controller'];
    }
    // Get the controller
}