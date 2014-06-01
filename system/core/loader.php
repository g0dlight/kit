<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

final class Loader{
    static public $controller = false;
    static public $errorHandler = false;
    static public $autoLoad = false;
    static public $duplicate = false;

    function __construct(){
        self::update();
        spl_autoload_register(__NAMESPACE__ .'\Loader::get');
    }

    static public function get($class){
        $class = strtolower($class);
        if(!isset(self::$autoLoad[$class]) || !file_exists(self::$autoLoad[$class].'/'.$class.'.php')){
            self::dumpAutoLoad();
        }
        if(isset(self::$autoLoad[$class])){
            require_once self::$autoLoad[$class].'/'.$class.'.php'.'';
            return true;
        }
        else return false;
    }

    static public function update(){
        $autoLoad = array();
        require 'app/settings/AutoLoad.php';
        self::$autoLoad = $autoLoad;
    }

    static public function dumpAutoLoad(){
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
                    if(!$arrayContent == '') $arrayContent .= ','.PHP_EOL;
                    $arrayContent .= "\t".'\''.$key.'\' => \''.$cleanAppFiles[$key].'\'';
                }
            }
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

    static public function scanFolder($folderPath){
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