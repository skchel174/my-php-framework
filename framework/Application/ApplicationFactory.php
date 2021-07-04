<?php

namespace Framework\Application;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApplicationFactory
{
    public function __invoke(ContainerInterface $container): Application
    {
        require BASE_DIR . '/setup/middlewares.php';
        return new Application(
            $container->get(MiddlewareDispatcherInterface::class),
            $container->get(ResponseEmitterInterface::class),
            $container->get(RequestHandlerInterface::class),
        );
    }
}
