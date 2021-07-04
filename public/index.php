<?php

use Framework\Application\Application;
use Framework\Http\Client\Request\ServerRequestFactory;

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

$container = require BASE_DIR . '/setup/container.php';

$request = (new ServerRequestFactory)->createFromSapi();
$app = $container->get(Application::class);
$app->run($request);
