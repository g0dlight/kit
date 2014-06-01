<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Output{
    static private $userRouter = '';
    static private $userConstructor = '';
    static private $userMethod = '';
    static private $userViews = '';
    static private $obStuck = '';

    static public function push($whereTo, $content){
        switch($whereTo){
            case 'userRouter':
                self::$userRouter .= $content;
                break;
            case 'constructor':
                self::$userConstructor .= $content;
                break;
            case 'method':
                self::$userMethod .= $content;
                break;
            case 'view':
                self::$userViews .= $content;
                break;
            case 'obStuck':
                self::$obStuck .= $content;
                break;
        }
    }

    static public function flush(){
        echo self::$userRouter;
        echo self::$userConstructor;
        echo self::$userMethod;
        echo self::$userViews;
        echo self::$obStuck;
    }
}