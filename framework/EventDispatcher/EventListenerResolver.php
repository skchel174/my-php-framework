<?php

namespace Framework\EventDispatcher;

use Framework\EventDispatcher\Interfaces\EventListenerInterface;
use Psr\Container\ContainerInterface;

class EventListenerResolver
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(EventListenerInterface|string $listener): EventListenerInterface
    {
        if (is_string($listener)) {
            $listener = new EventListenerWrapper($this->container, $listener);
        }
        return $listener;
    }
}
