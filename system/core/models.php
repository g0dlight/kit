<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Models{
    public function __get($name){
        $path = 'app/models/'.$name.'.php';
        if(file_exists($path)) require_once $path.'';
        else{
            throw new \Exception('Load model failed! `'.$name.'` not found');
        }
        return $this->$name = new $name;
    }
}