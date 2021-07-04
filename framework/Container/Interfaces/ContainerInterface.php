<?php

namespace Framework\Container\Interfaces;

use \Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    public static function getInstance(): static;
    public function set(string $id, mixed $value): void;
}
