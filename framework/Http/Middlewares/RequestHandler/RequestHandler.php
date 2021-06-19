<?php

namespace Framework\Http\Middlewares\RequestHandler;

use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    protected RequestHandlerResolver $resolver;

    public function __construct(RequestHandlerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var RouteInterface $route */
        $route = $request->getAttribute(Route::class);
        $handler = $this->resolver->resolve($route->getHandler());
        return $handler($request);
    }
}
