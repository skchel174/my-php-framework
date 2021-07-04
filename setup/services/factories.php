<?php

use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\Container\Container;
use Framework\Container\ServiceProvider;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;

$provider = Container::getInstance()->get(ServiceProvider::class);

$provider->factory(Application::class, ApplicationFactory::class);
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);
