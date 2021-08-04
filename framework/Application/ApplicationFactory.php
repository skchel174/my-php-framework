<?php

namespace Framework\Application;

use Framework\Http\Middlewares\AppMiddlewaresFactory;
use Framework\Http\RequestHandler\RequestHandler;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Psr\Container\ContainerInterface;

class ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        return new Application(
            $container->get(AppMiddlewaresFactory::class)($container),
            $container->get(ResponseEmitter::class),
            $container->get(RequestHandler::class),
        );
    }
}
