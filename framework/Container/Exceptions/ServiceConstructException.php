<?php

namespace Framework\Container\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use ReflectionException;

class ServiceConstructException extends ReflectionException implements ContainerExceptionInterface
{
    public function __construct(string $parameter, string $service)
    {
        $message = 'Parameter "' . $parameter . '" of class "' . $service . '" has no default value';
        parent::__construct($message, 500);
    }
}
