<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions;

class InvalidMiddlewareTypeException extends \InvalidArgumentException
{
    public function __construct($middleware)
    {
        $message = 'Middleware of unknown type; passed ' . gettype($middleware);
        parent::__construct($message, 500);
    }
}
