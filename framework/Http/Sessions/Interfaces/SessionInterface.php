<?php

namespace Framework\Http\Sessions\Interfaces;

interface SessionInterface
{
    /**
     * Стартует сессию.
     *
     * @return bool
     */
    public function start(): bool;

    /**
     * Возвращает идентификатор текущей сессии
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Устанавливает идентификатор текущей сессии
     *
     * @param string $id
     * @return bool|string
     */
    public function id(string $id): bool|string;

    /**
     * Возвращает имя текущей сессии
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Устанавливает имя текущей сессии.
     *
     * @param string $name
     * @return bool|string
     */
    public function name(string $name): bool|string;

    /**
     * Добавляет в массив сессии данные по идентификатору $key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void;

    /**
     * Возвращает значение сессии по ключу $key,
     * или весь массив сессий, если в качестве аргумента $key
     * передана пустая строка.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key = ''): mixed;

    /**
     * Проверяет, существует значение сессии по переданному ключу.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Удалает эелемент массива сессий по ключу $key.
     *
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool;

    /**
     * Полностью очищает массив сессий.
     */
    public function clear(): void;

    /**
     * Уничтожает данные сессии.
     */
    public function destroy(): bool;

    /**
     * Очищает массив сессии и удаляет cookie с ее идентификатором.
     */
    public function flush(): void;
}
