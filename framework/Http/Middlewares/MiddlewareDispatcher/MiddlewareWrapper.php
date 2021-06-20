<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher;

use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareWrapperInterface;
use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareWrapper является оберткой-адаптером над посредниками различных типов
 * с целью их использования в классах, реализующих стандарты PSR-15: HTTP Server Request Handlers
 *
 * @package Framework\Http\Middlewares\MiddlewareDispatcher
 */
class MiddlewareWrapper implements MiddlewareInterface, MiddlewareWrapperInterface
{
    /** @var callable $middleware */
    protected $middleware;
    protected array $routes = [];

    public function __construct(callable $middleware)
    {
        $this->middleware = $middleware;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return ($this->middleware)($request, $handler);
    }

    public function route(string|array $route): static
    {
        if (is_string($route)) {
            $this->routes[] = $route;
        }
        $this->routes = array_merge($this->routes, $route);
        return $this;
    }

    public function isAdmitted(ServerRequestInterface $request): bool
    {
        if (empty($this->routes)) {
            return true;
        }
        /** @var RouteInterface $route */
        $route = $request->getAttribute(Route::class);

        foreach ($this->routes as $item) {
            return $item === $route->getName() ?: $route->getPath();
        }
    }
}
