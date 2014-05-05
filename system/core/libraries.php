<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Libraries{
    public function __get($name){
        $path = 'app/libraries/'.$name.'/'.$name.'.php';
        if(file_exists($path)) require_once $path.'';
        else{
            throw new \Exception('Load library failed! `'.$name.'` not found');
        }
        return $this->$name = (class_exists($name, false))? new $name:true;
    }
}