<?php

namespace Framework\Http\MiddlewareDispatcher\Interfaces;

interface MiddlewareResolverInterface
{
    /**
     * Метод должен уметь обрабатывать строковые имена классов-посредников,
     * объекты-посредники, функции обратного вызова,
     * а также массивы с посредниками любого из перечисленных типа.
     *
     * Возвращает функцию обратного вызова - обертку принимающую объекты
     * типов Psr\Http\Message\ServerRequestInterface и Psr\Http\Server\RequestHandlerInterface.
     * Функция обеспечивает "ленивую загрузку" помещенных в нее посредников.
     *
     * Функция запускается в соотвествии с очередность вызовов посредников
     * при обработке приложением клиентского запроса.
     *
     * @param mixed $middleware
     * @return callable
     */
    public function resolve(mixed $middleware): callable;
}
