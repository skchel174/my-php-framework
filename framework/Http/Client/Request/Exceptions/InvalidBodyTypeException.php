<?php

namespace Framework\Http\Client\Request\Exceptions;

class InvalidBodyTypeException extends \InvalidArgumentException
{
    public function __construct($body, $code = 0, \Throwable $previous = null)
    {
        $message = 'The request body must be of type null, array or object; passed ' . gettype($body);
        parent::__construct($message, $code, $previous);
    }
}
