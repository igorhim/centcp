<?php

namespace Centcp\Model;

class DatabaseUser extends \Centcp\Model {
    
    public $table = 'database_user';
    
    public $key = 'dbu_id';
    
    public $fieldUser = 'dbu_user';
    
    public $password = 'dbu_password';
    
    public $fields = array(
        'dbu_name' => array(
            'type' => 'text', 
            'title' => 'Username', 
            'required' => true),
        'dbu_password' => array(
            'type' => 'password', 
            'title' => 'Password', 
            'required' => true,
            'hidden' => array('list')
            ),    
        'dbu_database' => array(
            'type' => 'select', 
            'title' => 'Database',
            'options' => array(),
            'required' => true),      
                
            );
    public $relations = array('dbu_user' => array('model' => 'user'), 'dbu_database' => array('model' => 'mysql'));
    
    public function save($item, $id = null) {
        //automatically add user prefix
        if($this->app->user->current['user_role'] !== 'admin') {
            $title = explode('_', $item['dbu_name']); 
            if($title[0] !== $this->app->user->current['user_login']) {
                $item['dbu_name'] = $this->app->user->current['user_login'] . '_' . $item['dbu_name'];
            }
        }
        
        return parent::save($item, $id);
    }
    
    /**
     * Execute server command
     */
    protected function exec($method, $params) {
        $db = new \Centcp\Database(array_merge(array('type' => 'mysql'), $this->app->settings['mysql']));
        $database = $this->app->mysql->getOne($params['dbu_database']);
        if($method == 'add') {
            //$db->query("CREATE USER '{$params['dbu_name']}'@'{$this->app->settings['mysql']['hostname']}' IDENTIFIED BY '{$params['dbu_password']}'; ");
            $db->query("GRANT ALL ON {$database['db_title']}.* TO '{$params['dbu_name']}'@'{$this->app->settings['mysql']['hostname']}' IDENTIFIED BY '{$params['dbu_password']}'; ");
            $db->query("FLUSH PRIVILEGES; ");
            return true;
        }
        if($method == 'delete') {
            $db->query("REVOKE ALL PRIVILEGES ON {$database['db_title']}.* FROM '{$params['dbu_name']}'@'{$this->app->settings['mysql']['hostname']}'; ");
            $db->query("FLUSH PRIVILEGES; ");
            return true;
        }
        //var_dump($method, $params);
        //die();
        //return $this->app->exec(get_class($this), $method, $params);
    }
    
}