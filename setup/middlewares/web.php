<?php

use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\MethodSpecifierMiddleware;

/** @var MiddlewareDispatcherInterface $dispatcher */

$dispatcher->add(MethodSpecifierMiddleware::class);
