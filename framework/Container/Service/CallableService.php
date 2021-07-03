<?php

namespace Framework\Container\Service;

use Framework\Container\Interfaces\ContainerInterface;

class CallableService extends Service
{
    public function __invoke(ContainerInterface $container): object
    {
        $reflection = new \ReflectionFunction($this->service);
        $arguments = $this->initParameters($container, $reflection->getParameters());
        return ($this->service)(...$arguments);
    }
}
