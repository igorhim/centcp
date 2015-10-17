<?php

namespace Centcp\Model;

class Ip extends \Centcp\Model {

    public $table = 'ip';

    public $key = 'ip_id';
    
    public $title = 'ip_value';

    public $fields = array(
        'ip_value' => array(
            'type' => 'text',
            'title' => 'Ip',
            'required' => true),
        'ip_netmask' => array(
            'type' => 'text',
            'title' => 'Netmask',
            'required' => true),
        'ip_interface' => array(
            'type' => 'text',
            'title' => 'Interface',
            'required' => true),
        'ip_shared' => array(
            'type' => 'checkbox',
            'title' => 'Shared',
            'required' => true));

}
