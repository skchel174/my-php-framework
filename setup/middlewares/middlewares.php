<?php

use Framework\Http\Middlewares\CSRFProtection\CSRFProtectionMiddleware;
use Framework\Http\Middlewares\CSRFProtection\CSRFProtectionStartMiddleware;
use Framework\Http\Middlewares\ErrorHandleMiddleware;
use Framework\Http\Middlewares\ApiOrWebMiddleware\ApiOrWebMiddleware;
use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RouteDispatchMiddleware;
use Framework\Http\Middlewares\SessionStartMiddleware;

/** @var MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(ErrorHandleMiddleware::class);
$dispatcher->add(SessionStartMiddleware::class);
$dispatcher->add(RouteDispatchMiddleware::class);

$dispatcher->add(CSRFProtectionStartMiddleware::class);
$dispatcher->add(CSRFProtectionMiddleware::class);

$dispatcher->add(ApiOrWebMiddleware::class);
//$dispatcher->add(AppPerformanceMiddleware::class);
