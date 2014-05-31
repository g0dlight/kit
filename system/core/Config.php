<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Config{
    static private $Config;

    function __construct(){
        $config = false;
        require_once 'app/settings/Config.php';
        self::$Config = $config;

        ## environment
        if(!isset(self::$Config['environment']) || self::$Config['environment'] != 'production')
            self::$Config['environment'] = 'development';

        ## error output
        if(!isset(self::$Config['error_output'])) self::$Config['error_output'] = '';

        ## default controller
        if(empty(self::$Config['default_controller']))
            self::$Config['default_controller'] = 'undefined';

        ## instruments
        if(!isset(self::$Config['instruments']['controllers']) || self::$Config['instruments']['controllers'] !== false)
            self::$Config['instruments']['controllers'] = true;

        if(!isset(self::$Config['instruments']['models']) || self::$Config['instruments']['models'] !== false)
            self::$Config['instruments']['models'] = true;

        if(!isset(self::$Config['instruments']['views']) || self::$Config['instruments']['views'] !== false)
            self::$Config['instruments']['views'] = true;

        if(!isset(self::$Config['instruments']['helpers']) || self::$Config['instruments']['helpers'] !== false)
            self::$Config['instruments']['helpers'] = true;

        if(!isset(self::$Config['instruments']['libraries']) || self::$Config['instruments']['libraries'] !== false)
            self::$Config['instruments']['libraries'] = true;
    }

    static public function set($name, $value){
        self::$Config[$name] = $value;
    }

    static public function get($name){
        if(!isset(self::$Config[$name])){
            return null;
        }
        return self::$Config[$name];
    }
}