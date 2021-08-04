<?php

use Framework\Application\Application;
use Framework\Http\Client\Request\ServerRequestFactory;

ini_set('display_errors', 1);

define('BASE_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));
define('START_MEMORY', memory_get_usage());

require_once BASE_DIR . '/vendor/autoload.php';

Application::run(
    (new ServerRequestFactory)->createFromSapi()
);
