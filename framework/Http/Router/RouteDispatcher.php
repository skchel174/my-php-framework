<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Exceptions\ParameterNotAssignException;
use Framework\Http\Router\Exceptions\RouteNotExistsException;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Interfaces\RouteInterface;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Psr\Http\Message\RequestInterface;

class RouteDispatcher implements RouteDispatcherInterface
{
    protected RoutesCollectionInterface $routes;

    public function __construct(RoutesCollectionInterface $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(RequestInterface $request): RouteInterface
    {
        $requestPath = $request->getUri()->getPath();

        foreach ($this->routes->getRoutes() as $route) {
            /** @var RouteInterface $route */
            if (!in_array($request->getMethod(), $route->getMethods())) {
                continue;
            }

            $keys = [];
            $path = preg_replace_callback('#\{([^\}]+)\}#', function ($matches) use ($route, &$keys) {
                $keys[] = $matches[1];
                $placeholder = !empty($route->getParams()) ? $route->getParam($matches[1]) : '[^/]+';
                return '(' . $placeholder . ')';
            }, $route->getPath());

            if (preg_match('#^' . $path . '$#', $requestPath, $matches)) {
                if ($values = array_slice($matches, 1)) {
                    $route->attributes(array_combine($keys, $values));
                }
                return $route;
            }
        }
        throw new RouteNotExistsException($requestPath);
    }

    public function route(string $name, array $params = []): string
    {
        foreach ($this->routes->getRoutes() as $route) {
            /** @var Route $route */
            if ($name != $route->getName()) {
                continue;
            }

            if (!$params && !$route->getParams()) {
                return $route->getPath();
            }

            $path = preg_replace_callback('#\{([^\}]+)\}#', function ($matches) use ($route, &$params) {
                if (!array_key_exists($matches[1], $params)) {
                    throw new ParameterNotAssignException($matches[1]);
                }
                $param = $params[$matches[1]];
                unset($params[$matches[1]]);
                return $param;
            }, $route->getPath());

            if ($params) {
                $queryString = [];
                foreach ($params as $key => $value) {
                    $queryString[] = $key . '=' . $value;
                }
                $path .= '?' . implode('&', $queryString);
            }
            return $path;
        }
        throw new RouteNotFoundException($name);
    }
}
