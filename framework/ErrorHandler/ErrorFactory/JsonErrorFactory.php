<?php

namespace Framework\ErrorHandler\ErrorFactory;

use Framework\Http\Client\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;

class JsonErrorFactory extends ErrorFactory
{
    private Run $whoops;
    private array $config;

    public function __construct(Run $whoops, array $config)
    {
        $this->whoops = $whoops;
        $this->config = $config;
    }

    public function create(\Exception $e): ResponseInterface
    {
        $code = $this->normalizeCode($e->getCode());

        if ($this->config['debug']) {
            $this->whoops->allowQuit(false);
            $this->whoops->writeToOutput(false);
            $this->whoops->pushHandler(new JsonResponseHandler);
            $data = $this->whoops->handleException($e);
        } else {
            $data = [
                'code' => $code,
                'message' => $e->getMessage(),
            ];
        }

        return new JsonResponse($data, $code);
    }
}
