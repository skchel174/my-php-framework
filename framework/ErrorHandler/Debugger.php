<?php

namespace Framework\ErrorHandler;

use Framework\Helpers\ContentTypeHelper;
use Psr\Http\Message\ServerRequestInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Debugger
{
    use ContentTypeHelper;

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
