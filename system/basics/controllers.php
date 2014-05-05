<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

abstract class Controllers{
    private $Construct = false;

    protected $Models;
    protected $Views;
    protected $Helpers;
    protected $Libraries;

    final function __construct(){
        if($this->Construct) return;
        $this->Construct = true;
        ####################################################################################
        $this->Models = Loader::$Models;
        $this->Views = Loader::$Views;
        $this->Helpers = Loader::$Helpers;
        $this->Libraries = Loader::$Libraries;
        ####################################################################################
        $this->constructor();
    }

    abstract public function constructor();
}