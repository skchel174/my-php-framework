<?php

namespace Framework\Http\Client\Uri\Exceptions;

class InvalidUriSchemeException extends \InvalidArgumentException
{
    public function __construct(string $scheme, $code = 0, \Throwable $previous = null)
    {
        $message = 'No valid schema found; passed ' . $scheme;
        parent::__construct($message, $code, $previous);
    }
}
