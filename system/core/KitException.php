<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

class KitException extends Exception{
    public function __construct($massage, $code=0){
        $trace = $this->getTrace();;
        if(isset($trace[0]['file'], $trace[0]['line'])){
            $this->file = $trace[0]['file'];
            $this->line = $trace[0]['line'];
        }
        parent::__construct($massage, $code);
    }
}