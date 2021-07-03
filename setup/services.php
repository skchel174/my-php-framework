<?php

use Framework\Container\ServiceProvider;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollectionFactory;

/** @var ServiceProvider $provider */

// Aliases
$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(ResponseEmitterInterface::class, ResponseEmitter::class);

// Factories
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);

