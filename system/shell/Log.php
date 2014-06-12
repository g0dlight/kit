<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

final class Log{
    public static $path = 'app/storage/logs/';
    public static $today = '';

    function __construct(){
        self::$today = strtotime(date('d.m.Y'));
    }

    public static function write($logName, $content){
        $file = self::$path.self::$today.'.'.$logName;
        $pre = PHP_EOL;
        if(@!file_get_contents($file, NULL, NULL, 0, 1)){
            $pre = '####### THIS LOG CREATED ON: '.date('d-m-Y').' #######'.$pre.$pre;
        }
        file_put_contents($file, $pre.$content, FILE_APPEND | LOCK_EX);
    }
}