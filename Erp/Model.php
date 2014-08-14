<?php

namespace Erp;


class Model
{
    function __construct($oe, $model)
    {
        $this->oe = $oe;
        $this->model = $model;
    }

    // TODO: support kwargs using call_kw
    function __call($method, $params)
    {
        if (!array_key_exists($method, $this->oe->urls)) {
            $url = '/web/dataset/call';
        } else {
            $url = $this->oe->urls[$method];
        }
        if (sizeof($params) == 0)
            $params = array();
        else
            $params = $params[0];
        return $this->oe->json($this->oe->base . $url, array(
                'model' => $this->model,
                'method' => $method,
                'args' => $params)
        );
    }
} 