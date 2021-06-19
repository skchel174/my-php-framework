<?php

namespace Framework\Http\Middlewares\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareDispatcherInterface extends MiddlewareInterface
{
    public function add(mixed $middleware): void;
}