<?php
spl_autoload_extensions('.php');
spl_autoload_register();

require_once 'system/core/ErrorHandler.php';

final class Kit{
    static public $Config = false;
    static public $AutoLoad = false;
    static public $Router = false;
    static public $UrlParts = false;
    static public $Controller = false;
    static public $Class = false;
    static public $Method = false;

    function __construct(){
        $this->getSettings();
        $this->getUrlParts();
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
        if(!count($accessPath)) return false;
        self::$UrlParts = $accessPath;
        return true;
    }
    // Divider PATH_INFO to array by `/` into $this->_UrlParts.
}