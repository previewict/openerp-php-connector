<?php

namespace Erp;


class Exception extends \Exception
{
    public $message;
    public $code;
    public $details;

    function __construct($message = "", $code = 0, $details = null)
    {
        parent::__construct($message, $code);
        $this->details = $details;
    }
} 