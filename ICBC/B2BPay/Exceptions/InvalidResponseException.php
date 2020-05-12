<?php

namespace ICBC\B2BPay\Exceptions;

class InvalidResponseException extends \Exception
{
    public $raw = [];
	
    public function __construct($message, $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}

?>