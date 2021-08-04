<?php

namespace Framework\Container;

use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\Command\CommandHandler;
use Framework\Command\CommandHandlerFactory;
use Framework\DB\MysqlDBFactory;
use Framework\DB\SqliteDBFactory;
use Framework\ErrorHandler\DefaultHandler;
use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorManagerFactory;
use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Router\RouteDispatcherFactory;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Http\Sessions\SqliteSessionFactory;
use Framework\Logger\LoggerFactory;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\Renderer;
use Framework\Renderer\RendererFactory;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    const CONFIG_ID = 'config';
    const CONFIG_DIR = BASE_DIR . '/setup/config';

    const SERVICES_FILE = BASE_DIR . '/setup/services/services.php';
    const FACTORIES_FILE = BASE_DIR . '/setup/services/factories.php';
    const CALLABLES_FILE = BASE_DIR . '/setup/services/callables.php';

    public function __invoke(): ContainerInterface
    {
        $container = new Container();
        $provider = $container->get(ServiceProvider::class);

        $this->config($container, $provider);
        $this->services($container, $provider);
        $this->factories($container, $provider);
        $this->callables($container, $provider);

        return $container;
    }

    protected function config(ContainerInterface $container, ServiceProvider $provider): void
    {
        $configLoader = new ConfigLoader(static::CONFIG_DIR);
        $provider->config(static::CONFIG_ID, $configLoader->load());
    }

    protected function services(ContainerInterface $container, ServiceProvider $provider): void
    {
        $provider->service(DefaultHandler::class)->argument('logger', 'default-logger');
        $provider->service(HtmlErrorFactory::class)->argument('templates', 'config.error.templates');

        $provider->alias(MiddlewareDispatcherInterface::class, MiddlewareDispatcher::class);
        $provider->alias(RendererInterface::class, Renderer::class);

        require static::SERVICES_FILE;
    }

    protected function factories(ContainerInterface $container, ServiceProvider $provider): void
    {
        $provider->factory(Application::class, ApplicationFactory::class);
        $provider->factory(ErrorManagerInterface::class, ErrorManagerFactory::class);
        $provider->factory(RouteDispatcherInterface::class, RouteDispatcherFactory::class);
        $provider->factory(RendererInterface::class, RendererFactory::class);

        //$provider->factory(SessionInterface::class, SessionFactory::class);
        $provider->factory(SessionInterface::class, SqliteSessionFactory::class);

        $provider->factory(MysqlDBFactory::class, MysqlDBFactory::class);
        $provider->factory(SqliteDBFactory::class, SqliteDBFactory::class);

        $provider->factory(CommandHandler::class, CommandHandlerFactory::class);

        $provider->factory('default-logger', LoggerFactory::class);

        require static::FACTORIES_FILE;
    }

    protected function callables(ContainerInterface $container, ServiceProvider $provider): void
    {
        require static::CALLABLES_FILE;
    }
}
