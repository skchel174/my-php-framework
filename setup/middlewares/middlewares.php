<?php

use Framework\Http\Middlewares\ErrorHandleMiddleware;
use Framework\Http\Middlewares\ApiOrWebMiddleware\ApiOrWebMiddleware;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RouteDispatchMiddleware;

/** @var MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(ErrorHandleMiddleware::class);
$dispatcher->add(RouteDispatchMiddleware::class);
//$dispatcher->add(AppPerformanceMiddleware::class);
$dispatcher->add(ApiOrWebMiddleware::class);
