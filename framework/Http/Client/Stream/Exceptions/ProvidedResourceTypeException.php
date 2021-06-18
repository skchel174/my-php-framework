<?php

namespace Framework\Http\Client\Stream\Exceptions;

class ProvidedResourceTypeException extends \InvalidArgumentException
{
    public function __construct($type, $code = 0, \Throwable $previous = null)
    {
        $message = 'Constructor parameter of class Stream must be of type resource; provided ' . gettype($type);
        parent::__construct($message, $code, $previous);
    }
}
