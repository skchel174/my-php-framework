<?php

namespace Framework\ErrorHandler\Interfaces;

interface DecoratorInterface
{
    public function wrapUp(HandlerInterface $handler): DecoratorInterface;
}
