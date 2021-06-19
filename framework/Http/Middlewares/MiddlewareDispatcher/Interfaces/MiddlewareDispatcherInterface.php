<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces;

interface MiddlewareDispatcherInterface
{
    public function add(mixed $middleware): void;
}
