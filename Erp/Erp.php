<?php

namespace Erp;

use Tivoka\Client;
use Erp\Exception;

class Erp
{

    /**
     * Add new json-rpc entry points here
     *
     */
    var $urls = array('read' => "/web/dataset/search_read",
        'authenticate' => "/web/session/authenticate",
        'get_session_info' => "/web/session/get_session_info",
        'destroy' => "/web/session/destroy");

    function __construct($url, $db, $debug = false)
    {
        $this->base = $url;
        $this->db = $db;
        $this->debug = $debug;
        $this->cookie = False;
    }

    /**
     * Logs in with a valid $login $password
     */
    public function login($login, $password)
    {
        $this->cookie = False; // will ask for a new cookie.
        $req = $this->authenticate(array(
            'base_location' => $this->base,
            'db' => $this->db,
            'login' => $login,
            'password' => $password,
        ));
        $this->session_id = $req['session_id'];
        $this->authenticated = $req["uid"] !== False;
        $this->uid = $req["uid"];

        return $this->authenticated;
    }

    public function logout()
    {
        $req = $this->destroy(); // doesn't seem to do what it should
        $this->session_id = False;
        $this->authenticated = False;

        return True;
    }

    /**
     * Logs in with a valid $session_id and HTTP $cookie
     */
    public function loginWithSessionId($session_id, $cookie)
    {
        $this->session_id = $session_id;
        $this->cookie = $cookie;

        try {
            $req = $this->get_session_info();
        } catch (Exception $e) {
            $this->authenticated = False;
            return False;
        }
        $this->authenticated = $req["uid"] !== False;
        $this->login = $req["login"];
        return $this->authenticated;
    }

    private function get_connection($url)
    {
        $conn = Client::connect($url);
        if ($this->cookie !== False)
            $conn->setHeader('Cookie', $this->cookie);
        return $conn;
    }

    function json($url, $params)
    {
        $req = $this->get_connection($url)->sendRequest('call', $params);
        if ($req->isError()) {
            if ($this->debug) {
                $errType = "Unknown JSON-RPC Error";
                switch ($req->error) {
                    case 200:
                        $errType = "OpenERP Error";
                        break;
                    case 100:
                        $errType = "OpenERP Authentication Error";
                }
                echo $errType . ":\n" . json_encode($req->errorData, JSON_PRETTY_PRINT);
            }
            throw new Exception('Error ' . $req->error . ': ' . $req->errorMessage,
                $req->error,
                $req->errorMessage,
                null,
                $req->errorData);
        }
        // Very bad way to support Set-Cookie
        if (isset($req->responseHeaders['Set-Cookie']))
            $this->cookie = $req->responseHeaders['Set-Cookie'];

        return $req->result;
    }

    function __call($method, $params)
    {
        if (!array_key_exists($method, $this->urls))
            throw new Exception("Method has no URL defined in class OpenERP.");
        $url = $this->urls[$method];
        if (sizeof($params) == 0)
            $params = array();
        else
            $params = $params[0];
        return $this->json($this->base . $url, $params);
    }

}