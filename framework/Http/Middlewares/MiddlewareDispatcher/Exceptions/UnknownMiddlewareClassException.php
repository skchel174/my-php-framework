<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions;

class UnknownMiddlewareClassException extends \InvalidArgumentException
{
    public function __construct($middleware)
    {
        $message = 'Middleware of unknown type; passed ' . get_class($middleware);
        parent::__construct($message, 500);
    }
}
