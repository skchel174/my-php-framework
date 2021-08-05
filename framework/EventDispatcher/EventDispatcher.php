<?php

namespace Framework\EventDispatcher;

use Framework\EventDispatcher\Interfaces\EventDispatcherInterface;
use Framework\EventDispatcher\Interfaces\EventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private EventListenersProvider $provider;

    public function __construct(EventListenersProvider $provider)
    {
        $this->provider = $provider;
    }

    public function dispatch(EventInterface $event)
    {
        if ($this->provider->has($event)) {
            foreach ($this->provider->get($event) as $listener) {
                $listener->handle($event);
            }
        }
    }
}
