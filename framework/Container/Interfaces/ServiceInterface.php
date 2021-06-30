<?php

namespace Framework\Container\Interfaces;

interface ServiceInterface
{
    public function shared(bool $shared = true): ServiceInterface;
    public function isShared(): bool;
    public function argument(string $name, mixed $argument): ServiceInterface;
    public function construct(ContainerInterface $container): object;
}
