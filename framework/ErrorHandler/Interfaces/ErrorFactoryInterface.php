<?php

namespace Framework\ErrorHandler\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ErrorFactoryInterface
{
    public function create(\Throwable $e, array $config): ResponseInterface;
}
