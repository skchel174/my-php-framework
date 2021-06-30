<?php

namespace Framework\Container\Exceptions;

class ServiceNotFoundException extends \InvalidArgumentException
{
    public function __construct(string $service)
    {
        parent::__construct('Unknown service with identifier: "' . $service . '"', 500);
    }
}
