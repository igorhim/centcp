<?php

namespace Centcp\Controller;

class User extends \Centcp\Controller {
    
    protected $rout = 'user';
    
    protected $menu = array('add');
    
    function __construct($app) {
        
        parent::__construct($app);
        
        if($this->app->user->current['user_role'] != 'admin') {
            $this->app->error_503();
        }
        
        $this->app->assign('menu', 'user');
        $this->app->assign('title', 'Users');
        $this->app->assign('subtitle', 'Control panel users');        
        $this->app->prependMeta('title', 'Users | ');
        
        $this->app->addPath(array('title' => 'Users', 'url' => '/user/'));
        
    }
    
    function index() {        
        return parent::lists();
    }
}