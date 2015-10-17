<?php

namespace Centcp\Model;

class Cron extends \Centcp\Model {
    
    public $table = 'cron';
    
    public $key = 'cron_id';
    
    public $fieldUser = 'cron_user';
    
    public $fields = array(
        'cron_command' => array(
            'type' => 'textarea', 
            'title' => 'Command', 
            'required' => true),
        'cron_minute' => array(
            'type' => 'text', 
            'title' => 'Minute', 
            'required' => true),
        'cron_hour' => array(
            'type' => 'text', 
            'title' => 'Hour', 
            'required' => true),
        'cron_day' => array(
            'type' => 'text', 
            'title' => 'Day', 
            'required' => true),
        'cron_month' => array(
            'type' => 'text', 
            'title' => 'Month', 
            'required' => true),
        'cron_day_week' => array(
            'type' => 'text', 
            'title' => 'Day of week', 
            'required' => true),            
            );
    public $relations = array('cron_user' => array('model' => 'user'));
    
}