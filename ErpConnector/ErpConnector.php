<?php

namespace ErpConnector;


class ErpConnector
{
    public $server;
    public $database;
    public $username;
    public $password;
    public $uid;

    public function __construct($username, $password, $database, $server)
    {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->uid = null;
    }
}