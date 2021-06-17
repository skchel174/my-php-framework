<?php

namespace Framework\Http\Client\UploadedFile\Exceptions;

class AlreadyMovedFileException extends \RuntimeException
{
    public function __construct($code = 0, \Throwable $previous = null)
    {
        $message = 'Stream is not available because the file has already been moved';
        parent::__construct($message, $code, $previous);
    }
}
