<?php

namespace Framework\Http\Router\Exceptions;

class ParameterNotAssignException extends \Exception
{
    public function __construct(string $parameter)
    {
        $message = 'Missing parameter "' . $parameter . '"';
        parent::__construct($message, 500);
    }
}
