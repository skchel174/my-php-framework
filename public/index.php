<?php

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Client\Request\ServerRequestFactory;
use Framework\Http\Middlewares\MiddlewareDispatcher\Interfaces\MiddlewareDispatcherInterface;
use Framework\Http\Middlewares\RequestHandler\RequestHandler;

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

require BASE_DIR . '/setup/provider.php';
require BASE_DIR . '/setup/middlewares.php';

$request = (new ServerRequestFactory)->createFromSapi();

/**
 * @var MiddlewareDispatcherInterface $middlewareDispatcher
 * @var ContainerInterface $container
 */
$response = $middlewareDispatcher->process($request, $container->get(RequestHandler::class));

header(sprintf('HTTP/%s %d %s',
    $response->getProtocolVersion(),
    $response->getStatusCode(),
    $response->getReasonPhrase()
));

foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $name, $value), false);
    }
}

echo $response->getBody();
