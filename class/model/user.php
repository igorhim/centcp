<?php

namespace Centcp\Model;

class User extends \Centcp\Model {

    public $table = 'user';

    public $key = 'user_id';

    public $password = 'user_password';

    public $title = 'user_login';

    public $fields = array(
        'user_login' => array('type' => 'text', 'title' => 'User login'),
        'user_password' => array('type' => 'password', 'title' => 'User password', 'hidden' => array('list')),
        'user_role' => array(
            'type' => 'select',
            'title' => 'User role',
            'options' => array(array('title' => 'Admin', 'value' => 'admin'), array('title' => 'User', 'value' => 'user'))));

    public function delete($id) {

        //Delete user crons
        $items = $this->app->cron->getBy(array('cron_user' => $id));
        foreach ($items as $item) {
            $this->app->cron->delete($item['cron_id']);
        }

        //Delete user dbs
        $items = $this->app->database->getBy(array('db_user' => $id));
        foreach ($items as $item) {
            $this->app->database->delete($item['db_id']);
        }

        //Delete user sites
        $items = $this->app->site->getBy(array('site_user' => $id));
        foreach ($items as $item) {
            $this->app->sites->delete($site['site_id']);
        }

        //Delete user
        parent::delete($id);
    }

}
