<?php
require dirname((dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'config.php';
require dirname((dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

use ErpConnector\Auth\Auth;

class ErpConnectTest extends PHPUnit_Framework_TestCase
{
    public $server;
    public $database;
    public $username;
    public $password;

    public function __construct($username = USERNAME, $password = PASSWORD, $database = DATABASE, $server = SERVER)
    {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;

        $this->Auth = new Auth($this->username, $this->password, $this->database, $this->server);
    }
} 