<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AppPerformanceMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $time = microtime(true) - START_TIME;
        $memory = memory_get_usage() - START_MEMORY;

        return $response
            ->withHeader('X-Time-Used', [$time])
            ->withHeader('X-Memory-Used', [$memory]);
    }
}
