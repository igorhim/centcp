<?php

namespace Centcp\Controller;

class Ip extends \Centcp\Controller {
    
    protected $rout = 'ip';
    
    protected $menu = array('add', 'sync');    

    function __construct($app) {        
        
        parent::__construct($app);
        
        if($this->app->user->current['user_role'] != 'admin') {
            $this->app->error_503();
        }
        
        $this->app->assign('menu', 'ip');
        $this->app->assign('title', 'Ip');
        $this->app->assign('subtitle', '');
        $this->app->prependMeta('title', 'Ip | ');

        $this->app->addPath(array('title' => 'Ip', 'url' => '/ip/'));
    }

    function index() {        
        return parent::lists();
    }
}
