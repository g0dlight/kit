<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

##########
## declare main loader instance all loaders
final class Loader{
    static public $Models;
    static public $Views;
    static public $Helpers;
    static public $Libraries;

    function __construct(){
        if(\Kit::$Config['instruments']['models']) self::$Models = new Models();
        if(\Kit::$Config['instruments']['views']) self::$Views = new Views();
        if(\Kit::$Config['instruments']['helpers']) self::$Helpers = new Helpers();
        if(\Kit::$Config['instruments']['libraries']) self::$Libraries = new Libraries();
    }
}

##########
## declare Models loader
final class Models{
    public function __get($name){
        $path = 'app/models/'.$name.'.php';
        if(!file_exists($path)) Errors::make('Loader failed! The model `'.$name.'` not found' ,true);
        else{
            require_once $path.'';
            return $this->$name = new $name;
        }
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
    }
}

##########
## declare Helpers loader
final class Helpers{
    public function __get($name){
        $path = 'app/helpers/'.$name.'.php';
        if(!file_exists($path)) Errors::make('Loader failed! The helper `'.$name.'` not found' ,true);
        else{
            require_once $path.'';
        }
        return $this->$name = (class_exists($name, false))? new $name:true;
    }
}

##########
## declare Libraries loader
final class Libraries{
    public function __get($name){
        $path = 'app/libraries/'.$name.'/'.$name.'.php';
        if(!file_exists($path)) Errors::make('Loader failed! The library `'.$name.'` not found' ,true);
        else{
            require_once $path.'';
        }
        return $this->$name = (class_exists($name, false))? new $name:true;
    }
}