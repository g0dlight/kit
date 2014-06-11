<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Errors{
    public static $catch = array();

    function __construct(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', false);
        ini_set('log_errors', true);

        set_error_handler(array('System\Core\Errors', 'nonfatal'));
    }

    public static function getTitle($errorNumber=0){
        $errorType = array(
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error',
            E_DEPRECATED         => 'Deprecated',
            E_USER_DEPRECATED    => 'User Deprecated'
        );
        if(isset($errorType[$errorNumber])) return $errorType[$errorNumber];
        else return 'Unknown Error '.$errorNumber;
    }

    public static function fatal($error){
        $error['fatal'] = true;
        $error['title'] = self::getTitle($error['type']);
        $error['file'] = str_replace(getcwd(), '', $error['file']);
        if(stripos($error['message'], 'Uncaught exception') !== false){
            $massage = stripos($error['message'], 'with message');
            $error['oldMessage'] = explode('\'', $error['message']);
            $error['message'] = 'Uncaught exception `'.$error['oldMessage'][1].'`';
            if($massage !== false){
                $error['title'] = $error['oldMessage'][1].'('.$error['title'].')';
                $error['message'] = implode('\'',array_slice($error['oldMessage'], 3, 1));
            }
        }
        self::$catch[] = $error;
    }

    public static function nonfatal($errorNumber, $errorMessage, $errorFileName, $errorLineNumber){
        if(error_reporting() == 0) return;
        $error['fatal'] = false;
        $error['title'] = self::getTitle($errorNumber);
        $error['type'] = $errorNumber;
        $error['message'] = $errorMessage;
        $error['file'] = str_replace(getcwd(), '', $errorFileName);
        $error['line'] = $errorLineNumber;
        self::$catch[] = $error;
    }

    public static function show(){
        require_once 'system/views/error.php';
    }
}