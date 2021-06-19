<?php

namespace Framework\Http\Router\Interfaces;

use Framework\Http\Router\Exceptions\ParameterNotAssignException;
use Framework\Http\Router\Exceptions\RouteNotExistsException;
use Framework\Http\Router\Exceptions\RouteNotFoundException;
use Psr\Http\Message\RequestInterface;

interface RouteDispatcherInterface
{
    /**
     * Метод выполняет поиск в коллекции роутов по параметрам, переданнм в объекте типа Request
     *
     * @param RequestInterface $request
     * @throws RouteNotExistsException
     * @return RouteInterface
     */
    public function dispatch(RequestInterface $request): RouteInterface;

    /**
     * Метод по по псевдониму, зарегистрированному на конкретный роут,
     * и массиву параметров формирует строковое представление uri
     *
     * @param string $name
     * @param array $params
     * @throws ParameterNotAssignException
     * @throws RouteNotFoundException
     * @return string
     */
    public function route(string $name, array $params = []): string;
}
