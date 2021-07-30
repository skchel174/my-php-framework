<?php

namespace Framework\Http\Middlewares;

use Framework\Http\Middlewares\ApiOrWebMiddleware\ApiMiddlewareDispatcher;
use Framework\Http\Middlewares\ApiOrWebMiddleware\WebMiddlewareDispatcher;
use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\MiddlewareDispatcher\MiddlewareDispatcher;
use Psr\Container\ContainerInterface;

class ApplicationMiddlewaresFactory
{
    const MIDDLEWARES = BASE_DIR . '/setup/middlewares/middlewares.php';
    const WEB_MIDDLEWARES = BASE_DIR . '/setup/middlewares/web.php';
    const API_MIDDLEWARES = BASE_DIR . '/setup/middlewares/api.php';

    public function __invoke(ContainerInterface $container): MiddlewareDispatcherInterface
    {
        $dispatcher = $container->get(MiddlewareDispatcher::class);

        $this->middlewares($dispatcher);
        $this->web($container->get(WebMiddlewareDispatcher::class));
        $this->api($container->get(ApiMiddlewareDispatcher::class));

        return $dispatcher;
    }

    protected function middlewares(MiddlewareDispatcherInterface $dispatcher): void
    {
        require static::MIDDLEWARES;
    }

    protected function web(MiddlewareDispatcherInterface $dispatcher): void
    {
        require static::WEB_MIDDLEWARES;
    }

    protected function api(MiddlewareDispatcherInterface $dispatcher): void
    {
        require static::API_MIDDLEWARES;
    }
}
