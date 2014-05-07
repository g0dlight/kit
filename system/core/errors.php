<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Errors{
    static public $catch = array();

    function __construct(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', false);
        ini_set('log_errors', true);

        set_error_handler(array('System\Core\Errors', 'nonfatal'));
    }

    static public function getTitle($errorNumber){
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
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );
        if(isset($errorType[$errorNumber])) return $errorType[$errorNumber];
        else return 'Unknown Error';
    }

    static public function make($message, $fatal=false){
        $backTrace = debug_backtrace()[1];
        if($fatal){
            $error['type'] = 1;
            $error['message'] = $message;
            $error['file'] = $backTrace['file'];
            $error['line'] = $backTrace['line'];
            self::fatal($error);
            exit();
        }
        else self::nonfatal(1, $message, $backTrace['file'], $backTrace['line']);
    }

    static public function fatal($error){
        $error['fatal'] = true;
        $error['title'] = self::getTitle($error['type']);
        $error['shortFile'] = str_replace(getcwd(), '', $error['file']);
        self::$catch[] = $error;
    }

    static public function nonfatal($errorNumber, $errorMessage, $errorFileName, $errorLineNumber){
        $error['fatal'] = false;
        $error['title'] = self::getTitle($errorNumber);
        $error['type'] = $errorNumber;
        $error['message'] = $errorMessage;
        $error['file'] = $errorFileName;
        $error['shortFile'] = str_replace(getcwd(), '', $errorFileName);
        $error['line'] = $errorLineNumber;
        //$error['backtrace'] = debug_backtrace();
        self::$catch[] = $error;
    }

    static public function show(){
        require_once 'system/views/error.php';
    }
}