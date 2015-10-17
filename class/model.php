<?php

namespace Centcp;

abstract class Model {
    
    protected $app;
    
    protected $db;
    
    public $error;
    
    function __construct($app) {
        $this->app = $app;
        $this->db = $this->app->db;
    }
    
    /**
     * Execute server command
     */
    protected function exec($method, $params) { 
        return $this->app->exec(get_class($this), $method, $params);
    }
    
    /**
     * QueryBuilder
     * 
     */
    
    private function qb() {
        return $this->db->queryBuilder();
    } 
    
    /** 
     * Encrypt passwords
     */
    
    private function ecryptPasswords($item) {
        if(!empty($this->password)) {
            $item[$this->password] = $this->app->salt($item[$this->password]);
        }
        return $item;
    }
    
    /**
     * Save entity
     */
    
    public function save($item, $id = null) {
        
        if(!empty($this->fieldUser)) {
            $item[$this->fieldUser] = $this->app->user->current['user_id'];
        }
        
        if(empty($id)) {
            if($this->exec('add', $item)) {
                $item = $this->ecryptPasswords($item);
                return $this->qb()->insert($this->table, $item)->prepare('insert')->execute()->lastInsertId();
            }
        }else {
            if($this->exec('save', $item)) {
                $item = $this->ecryptPasswords($item);
                $this->qb()->update($this->table)->set($item)->where($this->key, $id)->prepare('update')->execute();
                return true;
            }
        }
    }
    
    /**
     * Check POST submit
     */
    public function submit() {
        $fields = $this->checkRequired($_POST);
        if(!empty($fields)) {
            $this->save($fields, isset($_POST[$this->key]) ? $_POST[$this->key] : null);
        }else {
            $this->error = 'Required fields';
        }
    }
    
    /**
     * Check required fields
     */
    public function checkRequired($submitFields) {
        $returnedFields = array();
        foreach($this->fields as $field => $options) {
            if(!empty($submitFields[$field])) {
                $returnedFields[$field] = $submitFields[$field];
            }elseif(!empty($options['required'])) {
                //there is no required field found
                return false;
            }
        } 
        
        return $returnedFields;
    }
    
    /**
     * Delete entity
     */
    
    public function delete($id) {
        if(!empty($id)) {
            $item = $this->getOne($id);
            //if user is not admin remove only own items
            if(empty($this->fieldUser) || ($this->app->user->current['user_role'] == 'admin' || $item[$this->fieldUser] == $this->app->user->current['user_id'])) {
                if($this->exec('delete', $item)) { 
                    //echo 'ok';
                    $this->qb()->from($this->table)->where($this->key, $id)->prepare('delete')->execute();
                    return true;
                }
            }else {
                $this->app->error_503();
            }
        }
        return false;
    }
    
    /**
     * Get entity by fields
     */
    
    public function getBy($where = array(), $params = array()) {
        if(!empty($this->fieldUser) && $this->app->user->current['user_role'] !== 'admin') {
            $where[$this->fieldUser] = $this->app->user->current['user_id'];
        }
        return $objQuery = $this->qb()->from($this->table)->where($where)->setParams($params)->prepare('select')->fetchAll();        
    }
    
    /**
     * Get with replaced relations
     */
    public function getWithRelationsBy($where = array(), $params = array()) {
        $items = $this->getBy($where, $params);
        if(!empty($this->relations)) {
            foreach($this->relations as $field => $options) {
                $ids = array();
                foreach($items as $item) {
                    $ids[] = $item[$field];
                }
                
                $relations = $this->app->{$options['model']}->getBy(array($this->app->{$options['model']}->key => $ids));
                foreach($items as $i => $item) {
                    foreach($relations as $rel) {
                        if($items[$i][$field] == $rel[$this->app->{$options['model']}->key]) {
                            $items[$i][$field] = $rel[$this->app->{$options['model']}->title];
                        }
                    }
                }
            }
        }
        
        return $items;
    }
    
    /**
     * Get single entity by id
     */
    
    public function getOne($id) {
        $items = $this->getBy(array($this->key => $id));
        return !empty($items) ? current($items) : array();
    }
    
    /**
     * Get single entity by fileds
     */
    
    public function getOneBy($where, $params = array()) {
        $params['limit'] = 1;
        $items = $this->getBy($where, $params);
        return !empty($items) ? current($items) : array();        
    }
    
    /** 
     * Get fields with relations 
     */
    public function getFieldsWithRelations($options = array()) {
        $fields = $this->fields;
        //var_dump($options, $fields);
        foreach($fields as $alias => $field) {
            if(!empty($options['type']) && isset($field['hidden']) && in_array($options['type'], $field['hidden'])) {
                unset($fields[$alias]);
            }
        }
        if(!empty($this->relations)) {
            foreach($this->relations as $field => $options) {
                if(isset($fields[$field])) {
                    $relations = $this->app->{$options['model']}->getBy();
                    foreach($relations as $rel) {
                        $fields[$field]['options'][] = array('title' => $rel[$this->app->{$options['model']}->title], 'value' => $rel[$this->app->{$options['model']}->key]);
                    }
                }
            }
        }
        return $fields;
    }
}