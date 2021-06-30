<?php

namespace Framework\Container\Services;

use Framework\Container\Interfaces\ContainerInterface;

class CallableService extends Service
{
    public function construct(ContainerInterface $container): object
    {
        $reflection = new \ReflectionFunction($this->service);
        $arguments = $this->initParameters($container, $reflection->getParameters());
        return ($this->service)(...$arguments);
    }
}
