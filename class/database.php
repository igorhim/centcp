<?php

namespace Centcp;

use PDO, PDOExceptionl;

class Database {

    protected $pdo;

    function __construct($settings) {
        
        require_once dirname(__FILE__) . "/queryBuilder.php";
        
        if(empty($settings['type']) || $settings['type'] == 'mysql') {
            $config = sprintf('mysql:host=%s', $settings['hostname']);
            if(!empty($settings['db'])) {
                $config .= ';dbname=' . $settings['db'];
            }
            if(!empty($settings['port'])) {
                $config .= ';port=' . $settings['port'];
            }
            
            if(!isset($settings['username'])) {
                $settings['username'] = '';
            }
            if(!isset($settings['password'])) {
                $settings['password'] = '';
            }
            try {
                $this->pdo = new PDO($config, $settings['username'], $settings['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            }  
            catch(PDOException $e) {  
                echo $e->getMessage();  
            }
        }elseif($settings['type'] == 'sqlite') {
            try {
                $this->pdo = new \PDO('sqlite:' . $settings['db']);
            }
            catch(PDOException $e) {  
                echo $e->getMessage();  
            }
        }
    }
    
    function queryBuilder() {
        //var_dump($backtrace = debug_backtrace());
        return new QueryBuilder($this);
    }

    function query($sql) {
        try {
            $result = $this->pdo->query($sql);
        }
        catch (PDOException $e) {
            echo '<pre>';
            var_dump($e->getMessage());
            echo '</pre>';
        }
        return $result;
    }

    function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    function execute($sth, $values = array()) {
        if(!empty($values)) {
            $sth->execute($values);
        }else {
            $sth->execute();
        }
        if($sth->errorCode() != '00000') {
            $backtrace = current(debug_backtrace());
            var_dump($msg = join("\n", $sth->errorInfo()), $backtrace);
            die();    
        }
        return $sth;
    }
    
    function fetch(&$sth) {
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function fetchAll(&$sth, $key = null) {
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (empty($key)) {
            return $data;
        }
        $return = array();
        foreach ($data as $i => $row) {
            if (isset($row[$key])) {
                $return[$row[$key]] = $row;
            }
        }
        return $return;
    }
    
    function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    function close() {
        $this->pdo = null;
    }
}
