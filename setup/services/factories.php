<?php

use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\ServiceProvider;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;

/** @var ContainerInterface $container */
$provider = $container->get(ServiceProvider::class);

$provider->factory(ApplicationFactory::class, Application::class);
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);

