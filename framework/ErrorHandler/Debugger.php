<?php

namespace Framework\ErrorHandler;

use Framework\Helpers\ResponseTypeHelper;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Debugger
{
    use ResponseTypeHelper;

    private Run $whoops;

    public function __construct(Run $whoops)
    {
        $this->whoops = $whoops;
    }

    public function handle(\Exception $e, ServerRequestInterface $request): void
    {
        $type = $this->getResponseType($request);
        $whoopsHandler = $type === 'json' ? new JsonResponseHandler() : new PrettyPageHandler();

        $this->whoops->pushHandler($whoopsHandler);
        $this->whoops->handleException($e);
    }
}
