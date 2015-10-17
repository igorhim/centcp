<?php

namespace Centcp;

class App {

    public $db;

    public $controller;

    public $settings;

    protected $vars = array(
        'meta' => array('title' => 'CentCP', 'description' => ''),
        'path' => array(),
        'title' => 'CentCP',
        'subtitle' => '',
        'version' => '0.0.1',
        'copyrights' => '2015',
        'menu' => '/');

    function __construct() {
        require_once dirname(__file__) . "/../config/main.php";
        $this->settings = $settings;

        require_once dirname(__file__) . "/database.php";
        $this->db = new Database($this->settings['db']);

        require_once dirname(__file__) . "/controller.php";
        require_once dirname(__file__) . "/model.php";
    }

    // code for auto-load classes
    function __get($name) {
        if (method_exists($this, $name)) {
            return $this->$name;
        }
        if (isset($this->$name)) {
            return $this->$name;
        }

        if (@file_exists(dirname(__file__) . "/model/" . $name . ".php")) {
            require_once (dirname(__file__) . "/model/" . $name . ".php");
            $classname = 'Centcp\\Model\\' . ucfirst($name);
            //var_dump($classname);
            return $this->$name = new $classname($this);
        }

    }

    public function init() {
        session_start();
        $this->checkDB();
        return $this->getController();

    }

    private function checkDB() {
        //return true;
        $sth = $this->db->prepare('SELECT * FROM main.sqlite_master WHERE name="user";');
        $sth = $this->db->execute($sth);
        $table = $this->db->fetch($sth);

        //install DB structure
        if (empty($table)) {
            require_once dirname(__file__) . "/install.php";
            Install::create($this);
        }
    }

    function getController() {
        $uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $action = !empty($uri[1]) ? $uri[1] : 'dashboard';
        $method = !empty($uri[2]) ? $uri[2] : 'index';

        if ($action != 'login') {
            $this->checkUser();
        }

        $controllerPath = dirname(__file__) . "/controller/" . $action . ".php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $class = 'Centcp\\Controller\\' . ucfirst($action);
            $this->controller = new $class($this);

            if (method_exists($this->controller, $method)) {
                call_user_func_array(array($this->controller, $method), array_slice($uri, 3));
                return true;
            } else {
                $this->error_404();
            }
        } else {
            return $this->error_404();
        }
    }

    function exec($class, $method, $params = array()) {
        return true;
    }

    function salt($password) {
        return md5($this->settings['encryption']['prefix'] . $password . $this->settings['encryption']['suffix']);
    }

    function prependMeta($key, $value) {
        $this->vars['meta'][$key] = $value . $this->vars['meta'][$key];
    }
    
    function addPath($value) {
        $this->vars['path'][] = $value;
    }

    function assign($var, $value) {
        $this->vars[$var] = $value;
    }

    function fetch($template) {
        $templatePath = dirname(__file__) . "/../tmpl/" . $template;
        if (!file_exists($templatePath)) {
            echo "Template not found: $template";
            return false;
        }

        ob_start();
        extract($this->vars);
        include $templatePath;
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    function display($template) {
        echo $this->fetch($template);
    }

    function checkUser() {
        if (empty($_SESSION['user'])) {
            header('Location: /login/');
            exit;
        } else {
            $this->user->current = $this->user->getOne($_SESSION['user']);
            $this->assign('user', $this->user->current);
        }
    }

    function error_404() {
        die('404');
    }
    
    function error_503() {
        die('503');
    }
}
