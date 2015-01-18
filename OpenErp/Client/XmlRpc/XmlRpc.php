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

namespace OpenErp\Client\XmlRpc;


class XmlRpc
{
    /**
     * @var string
     */
    public $userAgent = 'XML-RPC Client';

    /**
     * @var
     */
    private $_host;
    /**
     * @var
     */
    private $_port;
    /**
     * @var string
     */
    private $_path = '';
    /**
     * @var string
     */
    private $_charset = 'utf-8';
    /**
     * @var
     */
    private $_lastRawResponse;
    /**
     * @var
     */
    private $_lastRequest;

    /**
     * @param $host
     * @param int $port
     * @param string $charset
     */
    public function __construct($host, $port = 80, $charset = 'utf-8')
    {
        $this->setHost($host);
        $this->setPort($port);
        $this->setCharset($charset);
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->_host;
    }

    /**
     * @param $host
     */
    public function setHost($host)
    {
        $this->_host = rtrim($host, '/');
    }

    /**
     * @param $charset
     */
    public function setCharset($charset)
    {
        $this->_charset = $charset;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->_charset;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->_port;
    }

    /**
     * @param $port
     */
    public function setPort($port)
    {
        $this->_port = (int)$port;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->_lastRawResponse;
    }

    /**
     * @return mixed
     */
    public function getLastRequest()
    {
        return $this->_lastRequest;
    }

    /**
     * @param $method
     * @param array $params
     * @return mixed|\SimpleXMLElement|string
     */
    public function call($method, $params = [])
    {
        if (function_exists('xmlrpc_encode_request')) {
            $options = [
                'encoding' => $this->getCharset(),
                'version' => 'xmlrpc',
                'escaping' => 'markup',
            ];
            $payload = xmlrpc_encode_request($method, $params, $options);
        } else {
            $payload = $this->encodeRequest($method, $params);
        }

        $this->_lastRequest = $payload;

        $context = stream_context_create([
            'http' => [
                'method' => "POST",
                'header' => $this->getDefaultHeader(),
                'content' => $payload
            ]
        ]);
        $uri = $this->getHost() . ':' . $this->getPort() . $this->getPath();
        $xml = file_get_contents($uri, false, $context);


        $this->_lastRawResponse = $xml;

        $response = new \SimpleXMLElement($xml);
        $response = json_encode($response);
        $response = json_decode($response, true);
        return $response;
    }

    /**
     * @return string
     */
    public function getDefaultHeader()
    {
        $headers = "";
        $headers .= "Content-Type: text/xml\r\n";
        $headers .= "User-Agent: " . $this->userAgent . "\r\n";
        return $headers;
    }

    /**
     * @param $method
     * @param array $params
     * @return string
     */
    public function encodeRequest($method, array $params)
    {
        $payload = '<?xml version="1.0" encoding="' . $this->getCharset() . '"?>' . "\r\n";
        $payload .= "\t" . '<methodCall>' . "\r\n";
        $payload .= "\t\t" . '<methodName>' . $method . '</methodName>' . "\r\n";
        $payload .= "\t\t" . '<params>' . "\r\n";

        foreach ($params as $param) {
            $payload .= "<param>\r\n" . $this->encodeParam($param) . "</param>\r\n";
        }

        $payload .= "\t\t" . '</params>' . "\r\n";
        $payload .= "\t" . '</methodCall>' . "\r\n";

        return $payload;
    }

    /**
     * @param $param
     * @return bool|string
     */
    public function encodeParam($param)
    {
        switch (gettype($param)) {
            case 'boolean':
                $encoded = '<value><boolean>' . $param . '</boolean></value>' . "\r\n";
                break;

            case 'double':
                $encoded = '<value><double>' . $param . '</double></value>' . "\r\n";
                break;

            case 'integer':
                $encoded = '<value><int>' . $param . '</int></value>' . "\r\n";
                break;

            case 'string':
                $encoded = '<value><string>' . $param . '</string></value>' . "\r\n";
                break;

            case 'array':
                $encoded = $this->encodeArray($param);
                break;

            default:
                $encoded = false;
                break;
        }
        return $encoded;
    }

    /**
     * @param $array
     * @return string
     */
    private function encodeArray($array)
    {
        if ($this->isAssoc($array)) {
            $encoded = '<struct>' . "\r\n";

            foreach ($array as $key => $value) {
                $encoded .= '<member>' . "\r\n";
                $encoded .= '<name>' . $key . '</name>' . "\r\n";
                $encoded .= $this->encodeParam($value);
                $encoded .= '</member>' . "\r\n";
            }
            $encoded .= '</struct>' . "\r\n";
        } else {
            $encoded = '<array>' . "\r\n";
            $encoded .= '<data>' . "\r\n";

            foreach ($array as $value) {
                $encoded .= $this->encodeParam($value);
            }
            $encoded .= '</data>' . "\r\n";
            $encoded .= '</array>' . "\r\n";
        }
        return $encoded;
    }

    /**
     * @param $array
     * @return bool
     */
    private function isAssoc($array)
    {
        if (is_array($array) && !is_numeric(array_shift(array_keys($array)))) {
            return true;
        }
        return false;
    }
}