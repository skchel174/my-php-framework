<?php

use App\Http\Middlewares\AppPerformanceMiddleware;

/** @var Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(AppPerformanceMiddleware::class);
