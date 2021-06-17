<?php

namespace Framework\Http\Client\UploadedFile\Exceptions;

class InvalidPathTypeException extends \InvalidArgumentException
{
    public function __construct($path, $code = 0, \Throwable $previous = null)
    {
        $message = 'Directory path must be of type string; passed ' . gettype($path);
        parent::__construct($message, $code, $previous);
    }
}
