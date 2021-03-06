<?php

namespace Framework\Http\RequestHandler\Exceptions;

class InvalidRequestHandlerTypeException extends \InvalidArgumentException
{
    public function __construct($handler)
    {
        $message = 'Request handler of unknown type; passed ' . gettype($handler);
        parent::__construct($message);
    }
}
