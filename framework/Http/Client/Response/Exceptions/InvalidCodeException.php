<?php

namespace Framework\Http\Client\Response\Exceptions;

class InvalidCodeException extends \InvalidArgumentException
{
    public function __construct($responseCode, $code = 0, \Throwable $previous = null)
    {
        $message = 'Invalid response code specified; passed ' . $responseCode;
        parent::__construct($message, $code, $previous);
    }
}
