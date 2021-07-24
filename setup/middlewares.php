<?php

use Framework\ErrorHandler\Middleware\ErrorHandleMiddleware;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RouteDispatchMiddleware;
use App\Http\Middlewares\AppPerformanceMiddleware;

/** @var MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(ErrorHandleMiddleware::class);
$dispatcher->add(RouteDispatchMiddleware::class);
$dispatcher->add(AppPerformanceMiddleware::class)->route('home');
