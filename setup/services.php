<?php

use Framework\Container\ServiceProvider;
use Framework\ErrorHandler\DefaultHandler;
use Framework\ErrorHandler\ErrorFactory\HtmlErrorFactory;
use Framework\ErrorHandler\ErrorManager;
use Framework\ErrorHandler\ErrorManagerFactory;
use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Framework\Http\Middlewares\AppMiddlewaresFactory;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\MiddlewareDispatcher\MiddlewareDispatcher;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;
use Framework\Http\ResponseEmitter\ResponseEmitter;
use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Http\Router\RouteDispatcher;
use Framework\Logger\LoggerFactory;
use Framework\Renderer\Interfaces\RendererInterface;
use Framework\Renderer\Renderer;
use Psr\Http\Server\RequestHandlerInterface;
use Framework\Application\Application;
use Framework\Application\ApplicationFactory;
use Framework\Http\Router\Interfaces\RoutesCollectionInterface;
use Framework\Http\Router\RoutesCollectionFactory;

/** @var ServiceProvider $provider */

// Services
$provider->service(Application::class)->argument('middlewares', AppMiddlewaresFactory::class);
$provider->service(DefaultHandler::class)->argument('logger', 'default-logger');
$provider->service(HtmlErrorFactory::class)->argument('templates', 'config.error.templates');

// Aliases
$provider->alias(RouteDispatcherInterface::class, RouteDispatcher::class);
$provider->alias(MiddlewareDispatcherInterface::class, MiddlewareDispatcher::class);
$provider->alias(RequestHandlerInterface::class, RequestHandler::class);
$provider->alias(ResponseEmitterInterface::class, ResponseEmitter::class);
$provider->alias(ErrorManagerInterface::class, ErrorManager::class);
$provider->alias(RendererInterface::class, Renderer::class);

// Factories
$provider->factory(AppMiddlewaresFactory::class, AppMiddlewaresFactory::class);
$provider->factory('default-logger', LoggerFactory::class)
    ->argument('config', 'config.log.default');
$provider->factory(RoutesCollectionInterface::class, RoutesCollectionFactory::class);
$provider->factory(ErrorManagerInterface::class, ErrorManagerFactory::class);
$provider->factory(RendererInterface::class, Framework\Renderer\RendererFactory::class);
