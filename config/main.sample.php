<?php

$settings = array(
    'timezone' => 'Europe/Kiev',
    'locale' => 'uk_UA.UTF8',  
    'db' => array(
        'type' => 'sqlite',
        'db' => dirname(__file__) . '/../db/database.sdb',
        ),
    'encryption' => array(
        'prefix' => '',
        'suffix' => '',
        ),
    'mysql' => array(
        'hostname' => 'localhost',
        'username' => '',
        'password' => ''),
    );
