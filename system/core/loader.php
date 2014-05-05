<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Loader{
    static public $Models;
    static public $Views;
    static public $Helpers;
    static public $Libraries;

    function __construct(){
        if(\Kit::$Config['instruments']['models']) self::$Models = new Models();
        if(\Kit::$Config['instruments']['views']) self::$Views = new Views();
        if(\Kit::$Config['instruments']['helpers']) self::$Helpers = new Helpers();
        if(\Kit::$Config['instruments']['libraries']) self::$Libraries = new Libraries();
    }
}