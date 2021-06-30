<?php

namespace Framework\Container\Exceptions;

class ServiceConstructException extends \ReflectionException
{
    public function __construct(string $parameter, string $service)
    {
        $message = 'Parameter "' . $parameter . '" of class "' . $service . '" has no default value';
        parent::__construct($message, 500);
    }
}
