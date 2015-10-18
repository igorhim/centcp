<?php

namespace Centcp\Model;

class Log extends \Centcp\Model {

    public $table = 'log';
    
    public $key = 'log_id';
    
    public $fieldUser = 'log_user';
    
    public $fields = array(
        'log_date' => array(
            'type' => 'text', 
            'title' => 'Date', 
            'required' => true),
        'log_module' => array(
            'type' => 'text',
            'title' => 'Module',
            'required' => true),
        'log_action' => array(
            'type' => 'text',
            'title' => 'Action',
            'required' => true),
        'log_target' => array(
            'type' => 'text', 
            'title' => 'Target'),
        'log_user' => array(
            'type' => 'select', 
            'title' => 'User',
            'options' => array(),
            'hidden' => array('form'))            
            );
    public $relations = array('log_user' => array('model' => 'user'));
}