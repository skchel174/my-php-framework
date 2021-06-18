<?php

namespace Framework\Http\Client\UploadedFile\Exceptions;

class FileMoveFailException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        $message = 'Error when moving uploaded file';
        parent::__construct($message, $code, $previous);
    }
}
