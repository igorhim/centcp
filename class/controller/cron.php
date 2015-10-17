<?php

namespace Centcp\Controller;

class Cron extends \Centcp\Controller {
    
    protected $rout = 'cron';
    
    protected $menu = array('add');    

    function __construct($app) {        
        
        parent::__construct($app);
        
        $this->app->assign('menu', 'cron');
        $this->app->assign('title', 'Crontab');
        $this->app->assign('subtitle', '');
        $this->app->prependMeta('title', 'Crontab | ');

        $this->app->addPath(array('title' => 'Crontab', 'url' => '/cron/'));
    }

    function index() {        
        return parent::lists();
    }
}