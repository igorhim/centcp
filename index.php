<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__FILE__) . "/class/app.php";

$app = new Centcp\App();

$app->init();