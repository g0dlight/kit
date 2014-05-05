<?php
namespace System\Core;

final class Loader{
    static public $Controllers = array();
    static public $Models = array();
    static public $Views = array();
    static public $Helpers = array();
    static public $Libraries = array();

    static private function getFile($path){
        $path = str_replace('.php', '', $path);
        $path = explode('/', $path);
        $file_name = array_pop($path);
        $path = implode("/", $path).'/'.$file_name.'.php';
        if(file_exists($path)){
            require_once $path;
            return ucfirst($file_name);
        }
        else{
            return false;
        }

    }

    static public function Controller($name){
        self::$Controllers[] = ucfirst($name);
        self::getFile('app/controllers'.$name.'.php');
    }

    static public function Model(){

    }

    static public function View(){

    }

    static public function Helper(){

    }

    static public function Library(){

    }
}