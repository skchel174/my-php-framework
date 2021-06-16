<?php

namespace Framework\Http\Client\Stream\Exceptions;

use Throwable;

class NotOpeningFileException extends \RuntimeException
{
    public function __construct($file, $code = 0, Throwable $previous = null)
    {
        $message = 'File "' . $file . '" cannot be opened.';
        parent::__construct($message, $code, $previous);
    }
}
