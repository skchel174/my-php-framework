<?php

use Framework\Container\ServiceProvider;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;

/** @var ServiceProvider $provider */

$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);
