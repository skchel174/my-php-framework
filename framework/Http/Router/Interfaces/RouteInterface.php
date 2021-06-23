<?php

namespace Framework\Http\Router\Interfaces;

interface RouteInterface
{
    /**
     * Возвращает относительный путь к ресурсу
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Возвращает имя класса, назначенного в качестве обработчика роута
     *
     * @return mixed
     */
    public function getHandler(): mixed;

    /**
     * Возвращает методы/методы, зарегистрированные на роут
     *
     * @return array
     */
    public function getMethods(): array;

    /**
     * Возвращает имя роута при его наличии
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Сеттер необязательного свойства роута,
     * которое может быть использовано для восстановления строкового представления роута
     *
     * @param string $name
     * @return $this
     */
    public function name(string $name): static;

    /**
     * Возвращает параметры, по которым фильтруются динамические компоненты пути к ресурсу
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * @param string $id
     * @return mixed
     */
    public function getParam(string $id): mixed;

    /**
     * Сеттер параметров,
     * в качестве ключа используется имя динамического компонента типа в фигурных скобках,
     * в качестве значения - регулярное выражение, соответствующее возможным входным параметрам
     *
     * @param array $params
     * @return $this
     */
    public function params(array $params): static;

    /**
     * Возвращает массив с аттрибутами
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Сеттер для мета-информации роута, полученной в результате его парсинга
     *
     * @param array $attributes
     * @return $this
     */
    public function attributes(array $attributes): static;
}
