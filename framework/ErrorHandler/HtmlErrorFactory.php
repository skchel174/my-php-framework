<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class HtmlErrorFactory implements ErrorFactoryInterface
{

    public function create(\Throwable $e, array $config): ResponseInterface
    {

    }
}
