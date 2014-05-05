<?php
spl_autoload_extensions('.php');
spl_autoload_register();

require_once 'system/core/ErrorHandler.php';

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

        if(require_once self::$Controller['Path'].self::$Controller['Name'].'.php'.''){
            if(class_exists(self::$Controller['Name'], false)){
                $Controller = new self::$Controller['Name']();
                if(isset(self::$UrlParts[0]) && method_exists($Controller, self::$UrlParts[0])){
                    self::$Controller['Method'] = array_shift(self::$UrlParts);
                }
                if(is_callable(array($Controller,self::$Controller['Method']))){
                    call_user_func_array(array($Controller, self::$Controller['Method']), self::$UrlParts);
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

        if(empty(self::$Config['environment']) || self::$Config['environment'] != 'production') self::$Config['environment'] = 'development';

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