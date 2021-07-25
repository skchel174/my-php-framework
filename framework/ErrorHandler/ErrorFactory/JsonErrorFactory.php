<?php

namespace Framework\ErrorHandler\ErrorFactory;

use Framework\Http\Client\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;

class JsonErrorFactory extends ErrorFactory
{
    private bool $debug;

    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function create(\Exception $e): ResponseInterface
    {
        if ($this->debug) {
            throw $e;
        }

        $code = $this->normalizeCode($e->getCode());
        return new JsonResponse(['code' => $code, 'message' => $e->getMessage()], $code);
    }
}
