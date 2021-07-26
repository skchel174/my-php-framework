<?php

namespace App\Http\Middlewares\ApiOrWebMiddleware;

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiOrWebMiddleware implements MiddlewareInterface
{
    const API_PREFIX = '/api';

    private MiddlewareDispatcherInterface $apiDispatcher;
    private MiddlewareDispatcherInterface $webDispatcher;

    public function __construct(
        MiddlewareDispatcherInterface $apiDispatcher,
        MiddlewareDispatcherInterface $webDispatcher
    )
    {
        $this->apiDispatcher = $apiDispatcher;
        $this->webDispatcher = $webDispatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dispatcher = $this->isApiRequest($request) ? $this->apiDispatcher : $this->webDispatcher;
        return $dispatcher->process($request, $handler);
    }

    protected function isApiRequest(ServerRequestInterface $request): bool
    {
        $path = $request->getUri()->getPath();
        return substr($path, 0, 4) === static::API_PREFIX;
    }
}
