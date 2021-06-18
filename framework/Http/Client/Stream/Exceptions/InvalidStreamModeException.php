<?php

namespace Framework\Http\Client\Stream\Exceptions;

use Throwable;

class InvalidStreamModeException extends \InvalidArgumentException
{
    public function __construct($mode, $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid stream mode; provided ' . $mode;
        parent::__construct($message, $code, $previous);
    }
}
