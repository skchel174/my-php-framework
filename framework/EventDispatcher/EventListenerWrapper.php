<?php

namespace Framework\EventDispatcher;

use Framework\EventDispatcher\Interfaces\EventInterface;
use Framework\EventDispatcher\Interfaces\EventListenerInterface;
use Psr\Container\ContainerInterface;

class EventListenerWrapper implements EventListenerInterface
{
    private ContainerInterface $container;
    private string $listener;

    public function __construct(ContainerInterface $container, string $listener)
    {
        $this->container = $container;
        $this->listener = $listener;
    }

    public function handle(EventInterface $event)
    {
        /** @var EventListenerInterface $listener */
        $listener = $this->container->get($this->listener);
        return $listener->handle($event);
    }
}