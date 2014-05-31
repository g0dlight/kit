<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Basics;

if(!defined('KIT_KEY')) exit('Access denied.');

use System\Core\Config;
use System\Core\Loader;

abstract class Primary{
    private $Construct = false;

    protected $Controllers;
    protected $Models;
    protected $Views;
    protected $Helpers;
    protected $Libraries;

    final function __construct(){
        if($this->Construct) return;
        $this->Construct = true;
        $config = Config::get('instruments');
        ####################################################################################
        if($config['controllers']) $this->Controllers = Loader::$Controllers;
        if($config['models']) $this->Models = Loader::$Models;
        if($config['views']) $this->Views = Loader::$Views;
        if($config['helpers'])$this->Helpers = Loader::$Helpers;
        if($config['libraries']) $this->Libraries = Loader::$Libraries;
        ####################################################################################
        $this->constructor();
    }

    abstract public function constructor();
}