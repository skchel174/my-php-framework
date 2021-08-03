<?php

use App\Http\Controllers\IndexController;
use Framework\Command\CommandHandler;
use Framework\Command\CommandHandlerFactory;
use Framework\Container\ServiceProvider;
use Framework\DB\MysqlDBFactory;
use Framework\DB\SqliteDBFactory;
use Framework\ErrorHandler\DefaultHandler;
use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorManagerFactory;
use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\Http\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Http\Sessions\Interfaces\SessionInterface;
use Framework\Http\Sessions\SessionFactory;
use Framework\Http\Sessions\SqliteSessionFactory;
use Framework\Logger\LoggerFactory;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\Renderer;
use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;

/**
 * @var Psr\Container\ContainerInterface $container
 * @var ServiceProvider $provider
 */

// Services
$provider->service(DefaultHandler::class)->argument('logger', 'default-logger');
$provider->service(HtmlErrorFactory::class)->argument('templates', 'config.error.templates');

$provider->service(IndexController::class)
    ->argument('sqlite', SqliteDBFactory::class)
    ->argument('mysql', MysqlDBFactory::class);

// Aliases
$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(MiddlewareDispatcherInterface::class, MiddlewareDispatcher::class);
$provider->alias(RendererInterface::class, Renderer::class);
$provider->alias(ErrorManagerInterface::class, (new ErrorManagerFactory)($container));

// Factories
$provider->factory(Application::class, ApplicationFactory::class);
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);
$provider->factory(RendererInterface::class, Framework\Renderer\RendererFactory::class);
//$provider->factory(SessionInterface::class, SessionFactory::class);

$provider->factory(SessionInterface::class, SqliteSessionFactory::class);


$provider->factory(MysqlDBFactory::class, MysqlDBFactory::class);
$provider->factory(SqliteDBFactory::class, SqliteDBFactory::class);

$provider->factory(CommandHandler::class, CommandHandlerFactory::class);

$provider->factory('default-logger', LoggerFactory::class);