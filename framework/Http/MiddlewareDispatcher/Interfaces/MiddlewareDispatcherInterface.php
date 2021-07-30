<?php

namespace Framework\Http\MiddlewareDispatcher\Interfaces;

interface MiddlewareDispatcherInterface
{
    public function add(mixed $middleware): MiddlewareWrapperInterface;
}
