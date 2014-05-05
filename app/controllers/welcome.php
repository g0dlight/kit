<?php if(!defined('KIT_KEY')) exit('Access denied.');

class Welcome extends System\Basics\Controllers{
    public $welcomeStr = '<h1>Welcome!</h1>';

    public function constructor(){
        $this->welcomeStr .= '<h2>This is from constructor</h2>';
    }

    public function index(){

        $headTitle = 'Welcome To Kit';
        $this->welcomeStr .= '<h3>This is from index method</h3>';

        $this->Views->load('welcome', array('title'=>$headTitle,'text'=>$this->welcomeStr));
    }
}