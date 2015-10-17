<?php

namespace Centcp\Controller;

class DatabaseUser extends \Centcp\Controller {
    
    protected $rout = 'databaseUser';
    
    protected $menu = array('add');    

    function __construct($app) {        
        
        parent::__construct($app);
        
        $this->app->assign('menu', 'databaseUser');
        $this->app->assign('title', 'MySQL User');
        $this->app->assign('subtitle', '');
        $this->app->prependMeta('title', 'MySQL User | ');

        $this->app->addPath(array('title' => 'MySQL User', 'url' => '/databaseUser/'));
    }

    function index() {        
        return parent::lists();
    }
}