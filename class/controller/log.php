<?php

namespace Centcp\Controller;

class Log extends \Centcp\Controller {
    
    protected $rout = 'log';
    
    protected $menu = array();
    
    function __construct($app) {
        
        parent::__construct($app);
        
        $this->app->assign('menu', 'log');
        $this->app->assign('title', 'Log');
        $this->app->assign('subtitle', '');        
        $this->app->prependMeta('title', 'Log | ');
        
        $this->app->addPath(array('title' => 'Log', 'url' => '/log/'));
        
    }
    
    function index() {        
        return parent::lists();
    }
}