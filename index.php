<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

$auth = new \ErpConnector\Auth\Auth(USERNAME, PASSWORD, DATABASE, SERVER);

var_dump($auth->login());