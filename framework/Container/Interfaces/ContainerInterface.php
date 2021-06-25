<?php

namespace Framework\Container\Interfaces;

use \Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public static function __callStatic(string $name, array $arguments): mixed;
    public static function getInstance(): ContainerInterface;
    public function set(string $id, mixed $value): void;
}
