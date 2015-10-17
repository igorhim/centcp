<?php

namespace Centcp\Controller;

class Mysql extends \Centcp\Controller {
    
    protected $rout = 'mysql';
    
    protected $menu = array('add');    

    function __construct($app) {        
        
        parent::__construct($app);
        
        $this->app->assign('menu', 'database');
        $this->app->assign('title', 'MySQL');
        $this->app->assign('subtitle', '');
        $this->app->prependMeta('title', 'MySQL | ');

        $this->app->addPath(array('title' => 'MySQL', 'url' => '/mysql/'));
    }

    function index() {        
        return parent::lists();
    }
}