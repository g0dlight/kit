<?php if(!defined('KIT_KEY')) exit('Access denied.');

class Welcome{
    public $welcomeStr = '<h1>Welcome!</h1>';

    public function __construct(){
        $this->welcomeStr .= '<h2>This is from constructor</h2>';
    }

    public function index(){
        $headTitle = 'Welcome To Kit';
        $this->welcomeStr .= '<h3>This is from index method</h3>';
        Views::load('welcome', array('title'=>$headTitle,'text'=>$this->welcomeStr));
    }
}