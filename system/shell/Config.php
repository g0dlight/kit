<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

final class Config{
    private static $Config;

    function __construct(){
        $config = false;
        require_once 'app/settings/Config.php';
        self::$Config = $config;

        ## environment
        if(!isset(self::$Config['environment']) || self::$Config['environment'] != 'production')
            self::$Config['environment'] = 'development';

        ## error log
        if(!isset(self::$Config['error_log'])) self::$Config['error_log'] = true;

        ## error output
        if(!isset(self::$Config['error_output'])) self::$Config['error_output'] = '';

        ## default controller
        if(empty(self::$Config['default_controller']))
            self::$Config['default_controller'] = 'undefined';
    }

    public static function set($name, $value){
        self::$Config[$name] = $value;
    }

    public static function get($name){
        if(!isset(self::$Config[$name])){
            return null;
        }
        return self::$Config[$name];
    }
}