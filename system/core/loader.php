<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Loader{
    public static $controller = false;
    public static $errorHandler = false;
    public static $autoLoad = false;
    public static $duplicate = false;

    function __construct(){
        self::update();
        spl_autoload_register(__NAMESPACE__ .'\Loader::get');
    }

    public static function get($class){
        $class = strtolower($class);
        if(!isset(self::$autoLoad[$class]) || !file_exists(self::$autoLoad[$class].'/'.$class.'.php')){
            if(\Config::get('environment') == 'development') self::dumpAutoLoad();
        }
        if(isset(self::$autoLoad[$class])){
            require_once self::$autoLoad[$class].'/'.$class.'.php'.'';
            return true;
        }
        else return false;
    }

    public static function update(){
        $autoLoad = array();
        require 'app/settings/AutoLoad.php';
        self::$autoLoad = $autoLoad;
    }

    public static function dumpAutoLoad(){
        $folders = array(
            'controllers',
            'helpers',
            'libraries',
            'models'
        );
        $cleanAppFiles = false;
        $arrayContent = '';
        $appFiles = array(
            'system/core/config.php',
            'system/core/router.php',
            'system/core/views.php'
        );
        foreach($folders as $folderName){
            $appFiles = array_merge($appFiles, self::scanFolder('app/'.$folderName));
        }
        if(is_array($appFiles)){
            foreach($appFiles as $file){
                $file = explode('.', $file);
                if(array_pop($file) == 'php'){
                    $path = explode('/', implode('.',$file));
                    $key = array_pop($path);
                    if(isset($cleanAppFiles[$key])){
                        if(!isset(self::$duplicate[$key])) self::$duplicate[$key][] = $cleanAppFiles[$key];
                        self::$duplicate[$key][] = implode('/',$path);
                        continue;
                    }
                    $cleanAppFiles[$key] = implode('/',$path);
                    if($arrayContent != '') $arrayContent .= ','.PHP_EOL;
                    $arrayContent .= "\t".'\''.$key.'\' => \''.$cleanAppFiles[$key].'\'';
                }
            }
        }
        if(self::$duplicate){
            $errorMsg = '';
            foreach(self::$duplicate as $name => $paths){
                $errorMsg .= '<br />`'.$name.'` -> ';
                foreach($paths as $key => $path){
                    if($key) $errorMsg .= '& ';
                    $errorMsg .= '`'.$path.'` ';
                }
            }
            Errors::make('Duplicate files! more details in the list:'.$errorMsg, true);
        }
        $file = 'app\settings\AutoLoad.php';
        $content = '<?php'.PHP_EOL;
        if($cleanAppFiles){
            $content .= '$autoLoad = array('.PHP_EOL;
            $content .= $arrayContent;
            $content .= PHP_EOL.');';
        }
        file_put_contents($file, $content);
        self::update();
    }

    public static function scanFolder($folderPath){
        $result = array();
        $folderFiles = array_diff(scandir($folderPath), Array('.','..'));
        foreach($folderFiles as $value){
            $value = $folderPath.'/'.$value;
            if(is_dir($value)){
               $result =  array_merge($result, self::scanFolder($value));
            }
            else $result[] = $value;
        }
        return $result;
    }
}