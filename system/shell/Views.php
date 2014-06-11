<?php if(!defined('KIT_KEY')) exit('Access denied.');
/*
 * #### Warning this is a SYSTEM FILE ####
 */

use System\Core\Loader;
use System\Core\Output;

final class Views{
    public static function make($path, $variables=array(), $returnContent=false){
        $path = 'app/views/'.$path;
        $result = Loader::getView($path,$variables);
        if($result === false) throw new KitException('`'.$path.'` View not found');
        else{
            if($returnContent) return $result;
            else{
                Output::push('view', $result);
                return true;
            }
        }
    }
}