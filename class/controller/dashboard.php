<?php

namespace Centcp\Controller;

class Dashboard extends \Centcp\Controller {
    
    function __construct($app) {
        
        parent::__construct($app);
        
        $this->app->assign('menu', '/');
        $this->app->assign('title', 'Dashboard');
        $this->app->assign('subtitle', '');        
        $this->app->prependMeta('title', 'Dashboard | ');
        
        $this->prepareHeader();
    }
    
    function index() {
        
        $this->app->display('header.php');
        $this->app->display('dashboard.php');
        $this->app->display('footer.php');
    }
    
}