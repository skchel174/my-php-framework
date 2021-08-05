<?php

namespace Framework\EventDispatcher;

use Framework\EventDispatcher\Interfaces\EventDispatcherInterface;
use Psr\Container\ContainerInterface;

class EventDispatcherFactory
{
    const EVENTS_FILE = BASE_DIR . '/bootstrap/events.php';

    public function __invoke(ContainerInterface $container): EventDispatcherInterface
    {
        $provider = $container->get(EventListenersProvider::class);
        $this->listeners($provider);
        return new EventDispatcher($provider);
    }

    protected function listeners(EventListenersProvider $provider)
    {
        return require static::EVENTS_FILE;
    }
}
