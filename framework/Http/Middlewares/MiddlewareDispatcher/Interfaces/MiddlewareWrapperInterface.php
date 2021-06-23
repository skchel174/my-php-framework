<?php

namespace Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface MiddlewareWrapperInterface - содержит методы для привязки посредника,
 * реализующего данных интерфейс к определенному роуту по его имени или относительному пути
 *
 * @package Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces
 */
interface MiddlewareWrapperInterface
{
    /**
     * Сеттер для имени роута или его относительного пути
     *
     * @param string|array $route
     * @return $this
     */
    public function route(string|array $route): static;

    /**
     * Метод, выдающий разрешение посреднику.
     * При отсутствии указания у посредника на конкретный роут, метод возвращает true.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function isAdmitted(ServerRequestInterface $request): bool;
}
