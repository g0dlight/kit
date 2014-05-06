<?php
/*
 * #### Warning this is a SYSTEM FILE ####
 */

namespace System\Core;

if(!defined('KIT_KEY')) exit('Access denied.');

use System\Core\Output;

final class Views{
    public static function load($filePath, $variablesArray=NULL,$resultIntoVariable=FALSE){
        $filePath = 'app/views/'.$filePath.'.php';
        if(!file_exists($filePath)) throw new \Exception('The view file: `'.$filePath.'` is not found!');
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