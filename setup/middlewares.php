<?php

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\RouteDispatchMiddleware;
use App\Http\Middlewares\AppPerformanceMiddleware;

/** @var ContainerInterface $container */
$middlewareDispatcher = $container->get(MiddlewareDispatcher::class);

$middlewareDispatcher->add(RouteDispatchMiddleware::class);
$middlewareDispatcher->add(AppPerformanceMiddleware::class)->route('home');
