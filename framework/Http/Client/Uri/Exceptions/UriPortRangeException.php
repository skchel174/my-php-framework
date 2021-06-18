<?php

namespace Framework\Http\Client\Uri\Exceptions;

class UriPortRangeException extends \InvalidArgumentException
{
    public function __construct(int $port, $code = 0, \Throwable $previous = null)
    {
        $message = 'Port must be in range established TCP and UDP ports; passed ' . $port;
        parent::__construct($message, $code, $previous);
    }
}
