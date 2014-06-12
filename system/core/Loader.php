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
    public static $reserved = array(
        'kit','loader','kitexception','shutdown','errors','router','output',
        'config','log','route','views'
    );
    public static $duplicate = false;

    function __construct(){
        spl_autoload_register(array('System\Core\Loader', 'getClass'));
        self::update();
    }

    public static function getClass($class){
        $class = strtolower($class);
        if(!isset(self::$autoLoad[$class]) || !file_exists(self::$autoLoad[$class].'/'.$class.'.php')){
            if(\Config::get('environment') == 'development') self::dumpAutoLoad();
        }
        if(@include_once self::$autoLoad[$class].'/'.$class.'.php'.'') return true;
        else return false;
    }

    public static function getView($path, $variables=array()){
        $path .= '.php';
        if(!file_exists($path)) return false;
        foreach((array)$variables as $key => $value){
            $$key = $value;
        }
        ob_start();
        include $path.'';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function update(){
        $system = array(
            'kitexception' => 'system/core',
            'config' => 'system/shell',
            'log' => 'system/shell',
            'route' => 'system/shell',
            'views' => 'system/shell'
        );
        $autoLoad = array();
        @include 'app/settings/AutoLoad.php';
        self::$autoLoad = array_merge($system, (array)$autoLoad);
    }

    public static function dumpAutoLoad(){
        $folders = array(
            'controllers',
            'helpers',
            'models'
        );
        $cleanAppFiles = false;
        $arrayContent = '';
        $appFiles = array();
        foreach($folders as $folderName){
            $appFiles = array_merge($appFiles, self::scanFolder('app/'.$folderName));
        }
        if(is_array($appFiles)){
            foreach($appFiles as $file){
                $file = explode('.', $file);
                if(array_pop($file) == 'php'){
                    $path = explode('/', implode('.',$file));
                    $key = array_pop($path);
                    if(in_array($key, self::$reserved)){
                        throw new \KitException('`'.$key.'` is Reserved file name');
                        continue;
                    }
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
            $errorMsg = '<ul>';
            foreach((array)self::$duplicate as $name => $paths){
                $errorMsg .= '<li>`'.$name.'` <b>IN</b> ';
                foreach($paths as $key => $path){
                    if($key) $errorMsg .= '& ';
                    $errorMsg .= '`'.$path.'` ';
                }
                $errorMsg .= '</li>';
            }
            $errorMsg .= '</ul>';
            throw new \KitException('Duplicate files!'.$errorMsg);
        }
        $file = 'app/settings/AutoLoad.php';
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
            else $result[] = strtolower($value);
        }
        return $result;
    }
}