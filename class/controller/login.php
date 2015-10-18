<?php
namespace Centcp\Controller;

class Login extends \Centcp\Controller {
    
    protected $rout = 'login';
    
    function index() {
        if(!empty($_POST['login']) && !empty($_POST['password'])) {
            $user = $this->app->user->getOneBy(array('user_login' => $_REQUEST['login'], 'user_password' => $this->app->salt($_REQUEST['password'])));
            if($user) {
                $_SESSION['user'] = $user['user_id'];
                if(!empty($user)) {
                    header('Location: /');
                    exit;
                }
            }else {
                $this->app->assign('error', true);
            }
        }
        $this->app->display('login.php');
    }
    
    function logout() {
        $_SESSION['user'] = 0;
        session_destroy();
        header('Location: /');
    }
}