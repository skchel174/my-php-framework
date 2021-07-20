<?php

namespace Framework\ErrorHandler\Interfaces;

interface WrapperInterface
{
    public function wrapUp(HandlerInterface $handler): WrapperInterface;
}
