<?php

namespace Framework\Http\Client\Request\Exceptions;

class InvalidMethodException extends \InvalidArgumentException
{
    public function __construct($method, $code = 0, \Throwable $previous = null)
    {
        $message = 'Not valid request method; passed: ' . $method;
        parent::__construct($message, $code, $previous);
    }
}
