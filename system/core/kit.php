<?php
spl_autoload_extensions('.php');
spl_autoload_register();

final class Kit{
    function __construct(){
        new System\Core\Controllers();
    }
}