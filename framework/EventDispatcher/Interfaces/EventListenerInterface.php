<?php

namespace Framework\EventDispatcher\Interfaces;

interface EventListenerInterface
{
    public function handle(EventInterface $event);
}
