<?php

namespace Framework\EventDispatcher;

use Framework\EventDispatcher\Exceptions\UnknownEventException;
use Framework\EventDispatcher\Interfaces\EventInterface;

class EventListenersProvider
{
    private array $listeners = [];
    private EventListenerResolver $resolver;

    public function __construct(EventListenerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function has(EventInterface|string $event): bool
    {
        $event = $this->getEventName($event);

        return array_key_exists($event, $this->listeners);
    }

    public function get(EventInterface|string $event): array
    {
        $event = $this->getEventName($event);

        $this->eventExistGuard($event);

        return $this->listeners[$event];
    }

    public function subscribe(string $event, mixed $listener): void
    {
        $listenerArray = is_array($listener) ? $listener : [ $listener ];
        $listeners = array_map([$this->resolver, 'resolve'], $listenerArray);

        if ($this->has($event)) {
            $listeners = array_merge($this->listeners[$event], $listeners);
        }

        $this->listeners[$event] = $listeners;
    }

    protected function getEventName(EventInterface|string $event): string
    {
        return is_string($event) ? $event : $event::class;
    }

    protected function eventExistGuard(string $event): void
    {
        if (!$this->has($event)) {
            throw new UnknownEventException($event);
        }
    }
}
