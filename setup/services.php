<?php

use Framework\Container\ServiceProvider;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RoutesCollectionFactory;

/** @var ServiceProvider $provider */

// Aliases
$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);

// Factories
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);

