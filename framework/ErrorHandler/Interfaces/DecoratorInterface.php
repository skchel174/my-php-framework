<?php

namespace Framework\ErrorHandler\Interfaces;

interface DecoratorInterface
{
    public function wrapHandler(HandlerInterface $handler);
    public function getHandler(): ?HandlerInterface;
}
