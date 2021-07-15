<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class JsonErrorFactory implements ErrorFactoryInterface
{

    public function create(\Throwable $e, array $config): ResponseInterface
    {

    }
}
