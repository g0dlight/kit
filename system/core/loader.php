<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

##########
## declare main loader instance all loaders
final class Loader{
    static public $Controllers;
    static public $Models;
    static public $Views;
    static public $Helpers;
    static public $Libraries;

    function __construct(){
        self::$Controllers = new Controllers();
        self::$Models = new Models();
        self::$Views = new Views();
        self::$Helpers = new Helpers();
        self::$Libraries = new Libraries();
    }
}

##########
## declare Controllers loader
final class Controllers{
    public function __get($fullPath){
        $fullPath = explode('/', $fullPath);
        $name = array_pop($fullPath);
        $path = 'app/controllers/'.implode('/', $fullPath);
        if(!file_exists($path.'/'.$name.'.php')) Errors::make('Loader failed! The controller `'.$name.'` not found in `'.$path.'`' ,true);
        else{
            require_once $path.'/'.$name.'.php'.'';
            return $this->$name = (class_exists($name, false))? new $name:true;
        }
        return false;
    }
}

##########
## declare Models loader
final class Models{
    public function __get($fullPath){
        $fullPath = explode('/', $fullPath);
        $name = array_pop($fullPath);
        $path = 'app/models/'.implode('/', $fullPath);
        if(!file_exists($path.'/'.$name.'.php')) Errors::make('Loader failed! The model `'.$name.'` not found in `'.$path.'`' ,true);
        else{
            require_once $path.'/'.$name.'.php'.'';
            return $this->$name = (class_exists($name, false))? new $name:true;
        }
        return false;
    }
}

##########
## declare Views loader
final class Views{
    public static function load($filePath, $variablesArray=NULL,$resultIntoVariable=FALSE){
        $filePath = 'app/views/'.$filePath.'.php';
        if(!file_exists($filePath)) Errors::make('The view file: `'.$filePath.'` is not found' ,true);
        else{
            if(is_array($variablesArray)){
                foreach($variablesArray as $key => $value){
                    $$key = $value;
                }
            }
            ob_start();
            include $filePath.'';
            $file = ob_get_contents();
            ob_end_clean();
            if($resultIntoVariable) return $file;
            else{
                Output::push('view', $file);
                return true;
            }
        }
        return false;
    }
}

##########
## declare Helpers loader
final class Helpers{
    public function __get($fullPath){
        $fullPath = explode('/', $fullPath);
        $name = array_pop($fullPath);
        $path = 'app/helpers/'.implode('/', $fullPath);
        if(!file_exists($path.'/'.$name.'.php')) Errors::make('Loader failed! The helper `'.$name.'` not found in `'.$path.'`' ,true);
        else{
            require_once $path.'/'.$name.'.php'.'';
            return $this->$name = (class_exists($name, false))? new $name:true;
        }
        return false;
    }
}

##########
## declare Libraries loader
final class Libraries{
    public function __get($fullPath){
        $fullPath = explode('/', $fullPath);
        $name = array_pop($fullPath);
        $path = 'app/libraries/'.implode('/', $fullPath);
        if(!file_exists($path.'/'.$name.'.php')) Errors::make('Loader failed! The library `'.$name.'` not found in `'.$path.'`' ,true);
        else{
            require_once $path.'/'.$name.'.php'.'';
            return $this->$name = (class_exists($name, false))? new $name:true;
        }
        return false;
    }
}