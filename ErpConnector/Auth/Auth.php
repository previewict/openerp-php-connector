<?php

namespace ErpConnector\Auth;

use ErpConnector\ErpConnector;
use xmlrpc_client;
use xmlrpcmsg;
use xmlrpcval;

class Auth extends ErpConnector
{
    public function login()
    {

        $sock = new xmlrpc_client($this->server . 'common');
        $msg = new xmlrpcmsg('login');
        $msg->addParam(new xmlrpcval($this->database, "string"));
        $msg->addParam(new xmlrpcval($this->username, "string"));
        $msg->addParam(new xmlrpcval($this->password, "string"));

        $resp = $sock->send($msg);

        if (isset($resp->value()->me)) {
            if (isset($resp->value()->me['int'])) {
                $this->uid = $resp->value()->me['int'];
                return 1;
            } elseif (isset($resp->value()->me['boolean']) && $resp->value()->me['boolean'] === false) {
                return -2;
            }
        }

        if ($resp->errno > 0) {
            return -1;
        }

        return null;
    }
}