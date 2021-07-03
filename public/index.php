<?php

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;
use Framework\Http\ResponseEmitter\Interfaces\ResponseEmitterInterface;

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

require BASE_DIR . '/setup/container.php';
require BASE_DIR . '/setup/middlewares.php';

$request = (new ServerRequestFactory)->createFromSapi();

/**
 * @var MiddlewareDispatcherInterface $middlewareDispatcher
 * @var ContainerInterface $container
 */
$response = $middlewareDispatcher->process($request, $container->get(RequestHandler::class));

$responseEmitter = $container->get(ResponseEmitterInterface::class);
$responseEmitter->emit($response);
