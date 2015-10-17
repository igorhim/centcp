<?php

namespace Centcp\Model;

class Mysql extends \Centcp\Model {
    
    public $table = 'database';
    
    public $key = 'db_id';
    
    public $fieldUser = 'db_user';
    
    public $title = 'db_title';
    
    public $fields = array(
        'db_title' => array(
            'type' => 'text', 
            'title' => 'Title', 
            'required' => true),
        'db_charset' => array(
            'type' => 'select', 
            'title' => 'Charset', 
            'required' => true,
            'options' => array(array('title' => 'utf-8', 'value' => 'utf-8'))),    
        'db_user' => array(
            'type' => 'select', 
            'title' => 'Owner',
            'options' => array(),
            'hidden' => array('form')),  
        'db_link' => array(
            'type' => 'link', 
            'title' => 'Users',
            'url' => '/databaseUser/',
            'link' => 'Edit', 
            'hidden' => array('form')),         
                
            );
            
    public $relations = array('db_user' => array('model' => 'user'));
    
    public function delete($id) {
        
        //Delete database users        
        $items = $this->app->databaseUser->getBy(array('dbu_database' => $id));
        foreach($items as $item) {
            $this->app->databaseUser->delete($item['dbu_id']);
        }
        //die();
        //Delete database
        parent::delete($id);        
    }
    
    public function save($item, $id = null) {
        //automatically add user prefix
        if($this->app->user->current['user_role'] !== 'admin') {
            $title = explode('_', $item['db_title']); 
            if($title[0] !== $this->app->user->current['user_login']) {
                $item['db_title'] = $this->app->user->current['user_login'] . '_' . $item['db_title'];
            }
        }
        
        return parent::save($item, $id);
    }
    
    /**
     * Execute server command
     */
    protected function exec($method, $params) {
        $db = new \Centcp\Database(array_merge(array('type' => 'mysql'), $this->app->settings['mysql']));
        if($method == 'add') {
            $db->query('CREATE DATABASE ' . $params['db_title']);
            return true;
        }
        if($method == 'delete') {
            $db->query('DROP DATABASE ' . $params['db_title']);
            return true;
        }
        // var_dump($method, $params);
        //die();
        //return $this->app->exec(get_class($this), $method, $params);
    }
    
}