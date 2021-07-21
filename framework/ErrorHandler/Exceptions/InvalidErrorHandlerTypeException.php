<?php

namespace Framework\ErrorHandler\Exceptions;

use Throwable;

class InvalidErrorHandlerTypeException extends \InvalidArgumentException
{
    public function __construct(mixed $handler)
    {
        parent::__construct('Called error handler of unknown type; passed ' . gettype($handler));
    }
}
