<?php

namespace Centcp;

class Install {
    
    static public function create($app) {
            $app->db->query('CREATE TABLE "cron" (
                                "cron_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "cron_minute"  TEXT,
                                "cron_hour"  TEXT,
                                "cron_day"  TEXT,
                                "cron_month"  TEXT,
                                "cron_day_week"  TEXT,
                                "cron_command"  TEXT,
                                "cron_user"  INTEGER
                                );
                                ');
            
            $app->db->query('CREATE TABLE "database" (
                                "db_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "db_title"  TEXT,
                                "db_charset"  TEXT,
                                "db_user"  INTEGER
                                );
                                ');
            
            $app->db->query('CREATE TABLE "database_user" (
                                "dbu_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "dbu_name"  TEXT,
                                "dbu_password"  TEXT,
                                "dbu_user"  INTEGER,
                                "dbu_database"  INTEGER
                                );
                                ');
            
            $app->db->query('CREATE TABLE "ip" (
                                "ip_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "ip_value"  TEXT,
                                "ip_netmask"  TEXT,
                                "ip_interface"  TEXT,
                                "ip_shared"  INTEGER
                                );
                                ');
            
            $app->db->query('CREATE TABLE "site" (
                                "site_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "site_domain"  TEXT,
                                "site_ip"  INTEGER,
                                "site_alias"  TEXT,
                                "site_user"  INTEGER
                                );
                                ');
            
            $app->db->query('CREATE TABLE "user" (
                                "user_id"  INTEGER PRIMARY KEY AUTOINCREMENT,
                                "user_login"  TEXT,
                                "user_password"  TEXT,
                                "user_lastlogin"  TEXT,
                                "user_role"  TEXT
                                );
                                ');
            
            $app->db->queryBuilder()->insert('user', array('user_id' => 1, 'user_login' => 'admin', 'user_role' => 'admin', 'user_password' => $app->salt('admin')))->prepare('insert')->execute();
    }
}