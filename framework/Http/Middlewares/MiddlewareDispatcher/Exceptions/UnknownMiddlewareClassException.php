<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher\Exceptions;

class UnknownMiddlewareClassException extends \InvalidArgumentException
{
    public function __construct($middleware)
    {
        $message = 'Middleware of unknown type; passed ' .  !is_object($middleware) ?: get_class($middleware);
        parent::__construct($message, 500);
    }
}
