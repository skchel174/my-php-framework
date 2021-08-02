<?php

namespace Framework\ErrorHandler\ErrorFactory;

use Framework\Http\Client\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;

class JsonErrorFactory extends ErrorFactory
{
    public function create(\Throwable $e): ResponseInterface
    {
        $code = $this->normalizeCode($e->getCode());
        return new JsonResponse(['code' => $code, 'message' => $e->getMessage()], $code);
    }
}
