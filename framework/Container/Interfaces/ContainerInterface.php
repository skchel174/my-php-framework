<?php

namespace Framework\Container\Interfaces;

use \Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Методы "get", "set", "has", управляющие хранящимися зависимостями,
 * исполюзуют их идентификаторы в качестве ключей доступа к сервисам.
 * Идентификатор сервиса может быть представлен как последовательность
 * разделенных точкой ключей глубоковложенных элемнтов.
 * Например, идентификатор "config.db.mysql" предоставляет настройки
 * для БД, которые находятся в массиве db, который, в свою очередь,
 * расположен в массиве config.
 */
interface ContainerInterface extends PsrContainerInterface
{
    public function set(string $id, mixed $value): void;
}
