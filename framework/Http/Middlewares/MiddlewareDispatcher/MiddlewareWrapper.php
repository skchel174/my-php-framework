<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MiddlewareWrapper
{
    protected mixed $middleware;
    protected ?string $route = null;

    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    public function process(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        return ($this->middleware)($request, new RequestHandlerWrapper($next));
    }

    public function route(string $route): static
    {
        $this->route = $route;
        return $this;
    }

    public function isAdmitted(ServerRequestInterface $request): bool
    {
        if ($this->route === null) {
            return true;
        }
        /** @var RouteInterface $route */
        $route = $request->getAttribute(Route::class);
        return $this->route === $route->getName() ?: $route->getPath();
    }
}
