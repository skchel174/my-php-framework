<?php

namespace Framework\Http\Middlewares;

use Framework\Http\Middlewares\ApiOrWebMiddleware\ApiMiddlewareDispatcher;
use Framework\Http\Middlewares\ApiOrWebMiddleware\ApiOrWebMiddleware;
use Framework\Http\Middlewares\ApiOrWebMiddleware\WebMiddlewareDispatcher;
use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\CSRFProtection\CSRFProtectionMiddleware;
use Framework\Http\Middlewares\CSRFProtection\CSRFProtectionStartMiddleware;
use Psr\Container\ContainerInterface;

class AppMiddlewaresFactory
{
    const MIDDLEWARES = BASE_DIR . '/setup/middlewares/middlewares.php';
    const WEB_MIDDLEWARES = BASE_DIR . '/setup/middlewares/web.php';
    const API_MIDDLEWARES = BASE_DIR . '/setup/middlewares/api.php';

    public function __invoke(ContainerInterface $container): MiddlewareDispatcherInterface
    {
        $dispatcher = $container->get(MiddlewareDispatcher::class);

        $this->middlewares($container, $dispatcher);
        $this->web($container, $container->get(WebMiddlewareDispatcher::class));
        $this->api($container, $container->get(ApiMiddlewareDispatcher::class));

        return $dispatcher;
    }

    protected function middlewares(ContainerInterface $container, MiddlewareDispatcherInterface $dispatcher): void
    {
        $dispatcher->add(ErrorHandleMiddleware::class);
        $dispatcher->add(ProtocolVersionMiddleware::class);
        $dispatcher->add(SessionStartMiddleware::class);
        $dispatcher->add(RouteDispatchMiddleware::class);

        $dispatcher->add(CSRFProtectionStartMiddleware::class);
        $dispatcher->add(CSRFProtectionMiddleware::class);

        $dispatcher->add(ApiOrWebMiddleware::class);

        require static::MIDDLEWARES;
    }

    protected function web(ContainerInterface $container, MiddlewareDispatcherInterface $dispatcher): void
    {
        $dispatcher->add(MethodSpecifierMiddleware::class);

        require static::WEB_MIDDLEWARES;
    }

    protected function api(ContainerInterface $container, MiddlewareDispatcherInterface $dispatcher): void
    {
        require static::API_MIDDLEWARES;
    }
}
