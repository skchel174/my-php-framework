<?php

namespace Framework\EventDispatcher\Interfaces;

interface EventDispatcherInterface
{
    public function dispatch(EventInterface $event);
}
