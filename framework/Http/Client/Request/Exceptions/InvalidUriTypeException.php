<?php

namespace Framework\Http\Client\Request\Exceptions;

class InvalidUriTypeException extends \InvalidArgumentException
{
    public function __construct($uri, $code = 0, \Throwable $previous = null)
    {
        $message = 'Uri scheme must be of type string or instance of UriInterface; passed ' . gettype($uri);
        parent::__construct($message, $code, $previous);
    }
}
