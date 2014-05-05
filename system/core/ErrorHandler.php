<?php
namespace System\Core\ErrorHandler;

use Kit;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', false);
ini_set('log_errors', true);

function getType($errorNumber){
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
// Get text error type by the error number.

function Normal($errorNumber, $errorMessage, $errorFileName, $errorLineNumber, $errorVariables){
    $error['type'] = $errorNumber;
    $error['message'] = $errorMessage;
    $error['file'] = $errorFileName;
    $error['line'] = $errorLineNumber;
    $error['title'] = getType($errorNumber);
    $error['variables'] = $errorVariables;
    if(Kit::$Config['environment'] == 'development') Flush($error);
}
// Error handling function.

function Fatal(){
    $error = error_get_last();
    if($error['type'] === E_ERROR || $error['type'] === E_PARSE){
        $error['title'] = getType($error['type']);
        if(Kit::$Config['environment'] == 'development') Flush($error);
    }
}
// Fatal error handler function.

function Flush($error){
    var_dump($error);
    die();
}
// Flush the the error out and die.

set_error_handler('System\Core\ErrorHandler\Normal'); // $old_error_handler =
register_shutdown_function('System\Core\ErrorHandler\Fatal');