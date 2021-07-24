<?php

namespace Framework\Application;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApplicationFactory
{
    const MIDDLEWARES_FILE = BASE_DIR . '/setup/middlewares.php';

    public function __invoke(ContainerInterface $container): Application
    {
        $middlewareDispatcher = $container->get(MiddlewareDispatcherInterface::class);
        $this->middlewares($middlewareDispatcher);

        return new Application(
            $middlewareDispatcher,
            $container->get(ResponseEmitterInterface::class),
            $container->get(RequestHandlerInterface::class),
        );
    }

    protected function middlewares(MiddlewareDispatcherInterface $dispatcher): void
    {
        require static::MIDDLEWARES_FILE;
    }
}
