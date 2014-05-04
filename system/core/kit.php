<?php
spl_autoload_extensions('.php');
spl_autoload_register();

final class Kit{
    private $UrlParts = false;
    private $Controller = false;
    private $Class = false;
    private $Method = false;
    private $Errors = false;
    private $ErrorPage = false;
    private $Config = false;
    private $AutoLoad = false;
    private $Router = false;

    function __construct(){
        $this->getSettings();
        $this->getUrlParts();
    }

    private function getSettings(){
        $config = false;
        $router = false;
        $autoLoad = false;

        require_once 'app/settings/config.php';
        require_once 'app/settings/router.php';
        require_once 'app/settings/autoLoad.php';

        ############
        ## Config ##
        ############
        $this->Config = $config;

        ############
        ## Router ##
        ############
        $this->Router = $router;

        ##############
        ## AutoLoad ##
        ##############
        $this->AutoLoad = $autoLoad;
    }
    // Get and check the default settings for `Config` && `Router` && `AutoLoad`.

    private function getUrlParts(){
        $accessPath = (isset($_SERVER['PATH_INFO']))? $_SERVER['PATH_INFO']:'/';
        $accessPath = explode('/', $accessPath);
        array_shift($accessPath);
        if($accessPath[0] == NULL) array_shift($accessPath);
        if(!count($accessPath)) return false;
        $this->UrlParts = $accessPath;
        return true;
    }
    // Divider PATH_INFO to array by `/` into $this->_UrlParts.
}