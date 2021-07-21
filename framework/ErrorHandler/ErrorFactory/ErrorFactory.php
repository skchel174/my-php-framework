<?php

namespace Framework\ErrorHandler\ErrorFactory;

use Framework\Http\Client\Response\Response;
use Psr\Http\Message\ResponseInterface;

abstract class ErrorFactory
{
    abstract public function create(\Exception $e): ResponseInterface;

    protected function normalizeCode(int $code): int
    {
        return array_key_exists($code, Response::REASON_PHRASES) ? $code : 500;
    }
}
