<?php

use Framework\Container\Container;
use Framework\Container\ServiceProvider;
use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorFactory\JsonErrorFactory;
use Framework\ErrorHandler\ErrorManager;
use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\Renderer;
use Framework\Renderer\TemplatesManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\ErrorHandler\HandlersCollection;
use Framework\ErrorHandler\HandlersCollectionFactory;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;
use Psr\Log\LoggerInterface;

$provider = Container::getInstance()->get(ServiceProvider::class);

// Services
$provider->service(TemplatesManager::class)->argument('directory', 'config.templates');
$provider->service(HtmlErrorFactory::class)->argument('config', 'config');
$provider->service(JsonErrorFactory::class)->argument('config', 'config');

// Aliases
$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(MiddlewareDispatcherInterface::class, MiddlewareDispatcher::class);
$provider->alias(RequestHandlerInterface::class, RequestHandler::class);
$provider->alias(ResponseEmitterInterface::class, ResponseEmitter::class);
$provider->alias(ErrorManagerInterface::class, ErrorManager::class);
$provider->alias(RendererInterface::class, Renderer::class);

// Factories
$provider->factory(Application::class, ApplicationFactory::class);
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);
$provider->factory(HandlersCollection::class, HandlersCollectionFactory::class);

// Callables
$provider->callable(LoggerInterface::class, function (ContainerInterface $container): LoggerInterface {
    $config = $container->get('config.log');
    $logger = new Logger($config['name']);
    $logger->pushHandler(new StreamHandler($config['file'], Logger::DEBUG));
    return $logger;
});