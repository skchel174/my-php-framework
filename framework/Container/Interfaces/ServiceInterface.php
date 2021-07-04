<?php

namespace Framework\Container\Interfaces;

/**
 * Интерфейс является оберткой для хранящихся в объекте клсса Container сервисов.
 */
interface ServiceInterface
{
    /**
     * Устанавливает статус сервиса-класса, определяющий необходимость сохранения
     * созданного им объекта для последующего использования в других местах приложения.
     * Объект, в случае переданного в качестве аргумента, значения true, приобретает черты паттерна Singleton.
     *
     * @param bool $shared
     * @return ServiceInterface
     */
    public function shared(bool $shared = true): ServiceInterface;

    /**
     * Сообщает статус "shared" сервиса.
     *
     * @return bool
     */
    public function isShared(): bool;

    /**
     * Метод позволяет указать значения параметров конструктора объекта,
     * хранящегося в объекте класса, реализующего данный интерфейс.
     * При создании объекта, значение параметра $argument будет использовано
     * в качестве идентификатора сервиса, содержащегося в объекте класса, реализующего ContainerInterface.
     * При отутствии в Контейнере значения, значение идентификатора будет подставлено на место параметра.
     *
     * @param string $name - имя параметра в конструкторе без знака $.
     * @param mixed $argument - аргумент для внедрения в конструктор
     * @return ServiceInterface
     */
    public function argument(string $name, mixed $argument): ServiceInterface;

    /**
     * Возвращает массив заданных пргументов конструктора сервиса.
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * Метод создает объект обернутый к класс, реализующий данный интерфейс.
     * Использует объект с интерфейсом ContainerInterface для внедрения в объект зависимостей.
     *
     * @param ContainerInterface $container
     * @return object
     */
    public function __invoke(ContainerInterface $container): object;
}
