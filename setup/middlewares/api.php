<?php

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use App\Http\Middlewares\AppPerformanceMiddleware;

/** @var MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(AppPerformanceMiddleware::class);
