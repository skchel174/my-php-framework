<?php

namespace Framework\Container\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
    public function __construct(string $service)
    {
        parent::__construct('Unknown service with identifier: "' . $service . '"', 500);
    }
}
