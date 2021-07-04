<?php

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Container\ServiceProvider;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\RouteDispatcher;
use Psr\Http\Server\RequestHandlerInterface;

/** @var ContainerInterface $container */
$provider = $container->get(ServiceProvider::class);

$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(MiddlewareDispatcherInterface::class, MiddlewareDispatcher::class);
$provider->alias(RequestHandlerInterface::class, RequestHandler::class);
$provider->alias(ResponseEmitterInterface::class, ResponseEmitter::class);
