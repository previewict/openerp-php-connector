<?php
/**
 * @Author G. M. Shaharia Azam <shaharia.azam@gmail.com>
 * @URI http://github.com/shahariaazam
 * @GitHub https://github.com/shahariaazam/openerp-php-connector
 *
 * OpenERP PHP Connector with PHP web application through XML-RPC. You can authenticated user from OpenERP
 * in your application and this library will help you to create records for OpenERP models, write new dataset
 * in OpenERP, Read records from the OpenERP model, Search records with all criteria from OpenERP.
 *
 * The idea came from to build startup company management system for my own Preview ICT Limited company.
 * And the coding structure, logic building idea came from another open source OpenERP PHP connector library
 * https://bitbucket.org/simbigo/openerp-api
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 OpenERP PHP Connector
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */


namespace OpenErp;

use OpenErp\Client\XmlRpc\XmlRpc;

/**
 * Class Erp
 * @package Erp\Erp
 */
class OpenErp
{
    /**
     * @var
     */
    private $_defaultPath = '';
    /**
     * @var
     */
    private $_client;
    /**
     * @var
     */
    private $_uid;
    /**
     * @var
     */
    private $_version;
    /**
     * @var
     */
    private $_db;
    /**
     * @var
     */
    private $_login;
    /**
     * @var
     */
    private $_password;

    /**
     * @param $host
     * @param string $charset
     */
    public function __construct($username, $password, $database, $host, $charset = 'utf-8')
    {
        $urlInfo = parse_url($host);
        $scheme = $urlInfo['scheme'];
        $host = $urlInfo['host'];
        $port = isset($urlInfo['port']) ? $urlInfo['port'] : 8080;

        $path = isset($urlInfo['path']) ? $urlInfo['path'] : null;
        if ($path !== null && trim($path, '/') != 'xmlrpc') {
            $this->_defaultPath = rtrim($path, '/');
        } else {
            $this->_defaultPath = '';
        }
        $this->_client = new XmlRpc($scheme . '://' . $host, $port, $charset);
        $this->login($database, $username, $password);
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->getClient()->getLastResponse();
    }

    /**
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->getClient()->getLastRequest();
    }

    /**
     * @return XmlRpc
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->getClient()->getCharset();
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->_uid;
    }

    /**
     * @param $db
     * @param $login
     * @param $password
     * @return int
     */
    public function login($db, $login, $password)
    {
        $this->_db = $db;
        $this->_login = $login;
        $this->_password = $password;

        $client = $this->getClient();
        $client->setPath('/xmlrpc/common');

        $response = $client->call('login', [$db, $login, $password]);
        $this->throwExceptionIfFault($response);

        if(isset($response['params']['param']['value']['int'])){
            $uid = (int)$response['params']['param']['value']['int'];
            $this->_uid = $uid;
            return $uid;
        }else{
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function version()
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/common');

        $response = $client->call('version');
        $this->throwExceptionIfFault($response);

        $version = $response['params']['param']['value']['struct']['member'][0]['value']['string'];
        $this->_version = $version;

        return $version;
    }

    /**
     * @param bool $extended
     * @return mixed
     */
    public function about($extended = false)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/common');

        $response = $client->call('about', [$extended]);
        $this->throwExceptionIfFault($response);

        return $response['params']['param']['value']['string'];
    }

    /**
     * @param null $db
     * @param null $login
     * @param null $password
     * @return mixed
     */
    public function getTimezone($db = null, $login = null, $password = null)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/common');

        $params = [$this->_db, $this->_login, $this->_password];

        $response = $client->call('timezone_get', $params);
        $this->throwExceptionIfFault($response);

        return $response['params']['param']['value']['string'];
    }

    /**
     * @param $model
     * @param $data
     * @return int
     */
    public function create($model, $data)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/object');

        $params = [$this->_db, $this->getUid(), $this->_password, $model, 'create', $data];

        $response = $client->call('execute', $params);
        $this->throwExceptionIfFault($response);

        return (int)$response['params']['param']['value']['int'];
    }

    /**
     * @param $model
     * @param $data
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function search($model, $data, $offset = 0, $limit = 1000)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/object');

        $params = [$this->_db, $this->getUid(), $this->_password, $model, 'search', $data, $offset, $limit];

        $response = $client->call('execute', $params);
        $this->throwExceptionIfFault($response);

        $response = $response['params']['param']['value']['array']['data'];

        if (!isset($response['value'])) {
            return [];
        }
        $ids = [];
        $response = $response['value'];
        
        if(sizeof($response) === 1){
            return (int)$response['int'];
        }
        
        foreach ($response as $value) {
            if(array_key_exists('int', $value)){
                $ids[] = (int)$value['int'];
            }else{
                $ids[] = (int)$value;
            }
        }
        return $ids;
    }

    /**
     * @param $model
     * @param $ids
     * @param array $fields
     * @return array
     */
    public function read($model, $ids, $fields = [])
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/object');

        $params = [$this->_db, $this->getUid(), $this->_password, $model, 'read', $ids, $fields];

        $response = $client->call('execute', $params);
        $this->throwExceptionIfFault($response);

        $response = $response['params']['param']['value']['array']['data'];

        if (!isset($response['value'])) {
            return [];
        }
        $records = [];

        // When only one item is fetched the value of result is a associative array.
        // As a result records will be an array with length 1 with an empty array inside.
        // The following check fixes the issue.
        if (count($ids) === 1) {
            $response = array($response['value']);
        } else {
            $response = $response['value'];
        }

        foreach ($response as $item) {
            $record = [];
            $recordItems = $item['struct']['member'];

            foreach ($recordItems as $recordItem) {
                $key = $recordItem['name'];
                $value = current($recordItem['value']);
                $record[$key] = $value;
            }
            $records[] = $record;
        }
        return $records;
    }

    /**
     * @param $model
     * @param $ids
     * @param $fields
     * @return bool|mixed|\SimpleXMLElement|string
     */
    public function write($model, $ids, $fields)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/object');

        $params = [$this->_db, $this->getUid(), $this->_password, $model, 'write', $ids, $fields];

        $response = $client->call('execute', $params);
        $this->throwExceptionIfFault($response);

        $response = (bool)$response['params']['param']['value']['boolean'];

        return $response;
    }

    /**
     * @param $model
     * @param $ids
     * @return bool|mixed|\SimpleXMLElement|string
     */
    public function unlink($model, $ids)
    {
        $client = $this->getClient();
        $client->setPath('/xmlrpc/object');

        $params = [$this->_db, $this->getUid(), $this->_password, $model, 'write', $ids];

        $response = $client->call('execute', $params);
        $this->throwExceptionIfFault($response);

        $response = (bool)$response['params']['param']['value']['boolean'];

        return $response;
    }

    /**
     * @param $response
     * @throws \Exception
     */
    public function throwExceptionIfFault($response)
    {
        if (isset($response['fault'])) {
            $faultArray = $response['fault']['value']['struct']['member'];
            $faultCode = 0;
            $faultString = 'Undefined fault string';

            foreach ($faultArray as $fault) {
                if ($fault['name'] == 'faultCode') {
                    $f = $fault['value'];
                    if (isset($f['string'])) {
                        $faultCode = 0;
                        $faultString = $f['string'];
                        break;
                    }
                    if (isset($f['int'])) {
                        $faultCode = $f['int'];
                    }
                }
                if ($fault['name'] == 'faultString') {
                    $faultString = $fault['value']['string'];
                }
            }

            throw new \Exception($faultString, $faultCode);
        }
    }
}
