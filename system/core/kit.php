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
        $this->getUrlParts();
    }

    private function getUrlParts(){
        $accessPath = (isset($_SERVER['PATH_INFO']))? $_SERVER['PATH_INFO']:'/';
        $accessPath = explode('/', $accessPath);
        array_shift($accessPath);
        if($accessPath[0] == NULL) array_shift($accessPath);
        if(!count($accessPath)) return false;
        $this->UrlParts = $accessPath;
    }
    // Divider PATH_INFO to array by `/` into $this->_UrlParts.
}