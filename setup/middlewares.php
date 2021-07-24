<?php

use Framework\ErrorHandler\Middleware\ErrorHandleMiddleware;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RouteDispatchMiddleware;
use App\Http\Middlewares\AppPerformanceMiddleware;

/** @var MiddlewareDispatcherInterface $middlewareDispatcher */

$middlewareDispatcher->add(ErrorHandleMiddleware::class);
$middlewareDispatcher->add(RouteDispatchMiddleware::class);
$middlewareDispatcher->add(AppPerformanceMiddleware::class)->route('home');

return $middlewareDispatcher;
