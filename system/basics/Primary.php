<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Basics;

if(!defined('KIT_KEY')) exit('Access denied.');

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
        ####################################################################################
        if(\Kit::$Config['instruments']['controllers']) $this->Controllers = Loader::$Controllers;
        if(\Kit::$Config['instruments']['models']) $this->Models = Loader::$Models;
        if(\Kit::$Config['instruments']['views']) $this->Views = Loader::$Views;
        if(\Kit::$Config['instruments']['helpers'])$this->Helpers = Loader::$Helpers;
        if(\Kit::$Config['instruments']['libraries']) $this->Libraries = Loader::$Libraries;
        ####################################################################################
        $this->constructor();
    }

    abstract public function constructor();
}