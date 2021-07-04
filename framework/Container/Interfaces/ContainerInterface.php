<?php

namespace Framework\Container\Interfaces;

use \Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Класс, реализующий данный интерфейс, должен соответствовать паттерну "Singleton".
 *
 * Метод "getInstance" возвращает единственный экземпляр класса,
 * для использоваия хранящихся в нем сервисов в любой точке приложения.
 *
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
    public static function getInstance(): static;
    public function set(string $id, mixed $value): void;
}
