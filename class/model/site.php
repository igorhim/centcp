<?php

namespace Centcp\Model;

class Site extends \Centcp\Model {

    public $table = 'site';

    public $key = 'site_id';
    
    public $title = 'site_domain';
    
    public $fieldUser = 'site_user';

    public $fields = array(
        'site_domain' => array(
            'type' => 'text', 
            'title' => 'Site domain', 
            'required' => true),
        'site_ip' => array(
            'type' => 'select',
            'title' => 'Site ip',
            'options' => array(),
            'required' => true),
        'site_alias' => array(
            'type' => 'textarea', 
            'title' => 'Site aliases'),
        'site_user' => array(
            'type' => 'select', 
            'title' => 'Site user',
            'options' => array(),
            'hidden' => array('form'))            
            );
    public $relations = array('site_ip' => array('model' => 'ip'), 'site_user' => array('model' => 'user'));

}
