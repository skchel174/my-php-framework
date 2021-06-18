<?php

namespace Framework\Http\Client\Response\Exceptions;

class InvalidPhraseTypeException extends \InvalidArgumentException
{
    public function __construct($phrase, $code = 0, \Throwable $previous = null)
    {
        $message = 'Response phrase must be of type string; passed ' . gettype($phrase);
        parent::__construct($message, $code, $previous);
    }
}
