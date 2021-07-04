<?php

use Framework\Container\ServiceProvider;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\RouteDispatcher;

/** @var ServiceProvider $provider */

$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(ResponseEmitterInterface::class, ResponseEmitter::class);