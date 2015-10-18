<?php

namespace Centcp;

class Controller {
    
    protected $app;
    
    function __construct($app) {
        $this->app = $app;
    }
    
    function lists() {
        $this->prepareHeader();
        $items = $this->app->{$this->rout}->getWithRelationsBy();
        
        $url = array('edit' => '/' . $this->rout . '/edit/{' . $this->app->{$this->rout}->key . '}');
        if(in_array('add', $this->menu)) {
           $url['add'] = '/' . $this->rout . '/add'; 
        }
        if(in_array('sync', $this->menu)) {
           $url['sync'] = '/' . $this->rout . '/sync'; 
        }
        

        $this->app->assign('list', array(
            'title' => 'List',
            'url' => $url,
            'data' => $items,
            'key' => $this->app->{$this->rout}->key,
            'fields' => $this->app->{$this->rout}->fields));
        
        $this->app->display('header.php');
        $this->app->display('list.php');
        $this->app->display('footer.php');
    }
    
    function add() {
        $this->prepareHeader();
        if(!empty($_POST)) {
            $this->app->{$this->rout}->submit();
            header('Location: /' . $this->rout . '/');
            exit;
        }
        //$this->app->assign('title', 'Add site');
        //$this->app->assign('subtitle', 'Web domains');
        $this->app->prependMeta('title', 'Add | ');
        $this->app->addPath(array('title' => 'Add', 'url' => '/' . $this->rout . '/add'));
        
        $fields = $this->app->{$this->rout}->getFieldsWithRelations(array('type' => 'form'));       
        $form = array('title' => 'Add', 'action' => '', 'box' => 'primary', 'fields' => $fields);
        $this->app->assign('form', $form);
        
        $this->app->display('header.php');
        $this->app->display('form.php');
        $this->app->display('footer.php');
    }
    
    function edit($id) {
        $this->prepareHeader();
        if(!empty($_POST)) {
            $this->app->{$this->rout}->submit();
            header('Location: /' . $this->rout . '/');
            exit;
        }
        //$this->app->assign('title', 'Add site');
        //$this->app->assign('subtitle', 'Web domains');
        $this->app->prependMeta('title', 'Edit| ');
        $this->app->addPath(array('title' => 'Edit', 'url' => '/site/edit'));
        
        $item = $this->app->{$this->rout}->getOne($id);
        
        $fields = $this->app->{$this->rout}->getFieldsWithRelations(array('type' => 'form'));        
        $form = array('title' => 'Edit', 'action' => '', 'box' => 'primary', 'fields' => $fields, 'item' => $item, 'key' => $this->app->{$this->rout}->key);
        $this->app->assign('form', $form);
        
        $this->app->display('header.php');
        $this->app->display('form.php');
        $this->app->display('footer.php');
    }
    
    function delete() {
        if(!empty($_POST['items'])) {
            foreach($_POST['items'] as $id) {
                //echo $id;
                $this->app->{$this->rout}->delete($id);
            }
        }
    }
    
    function prepareHeader() {
        $where = array();
        if($this->app->user->current['user_role'] !== 'admin') {
            $where['log_user'] = $this->app->user->current['user_id'];
        }
        
        $notifications = $this->app->log->getWithRelationsBy($where, array('order' => array('log_id' => 'DESC'), 'limit' => 10));
        $this->app->assign('notifications', $notifications);
    }
}