<?php

namespace Centcp\Controller;

class Site extends \Centcp\Controller {
    
    protected $rout = 'site';
    
    protected $menu = array('add');
    
    function __construct($app) {
        
        parent::__construct($app);
        
        $this->app->assign('menu', 'site');
        $this->app->assign('title', 'Sites');
        $this->app->assign('subtitle', 'Web domains');        
        $this->app->prependMeta('title', 'Sites | ');
        
        $this->app->addPath(array('title' => 'Sites', 'url' => '/site/'));
        
    }
    
    function index() {        
        return parent::lists();
    }
}