<?php

use Framework\Container\ConfigLoader;
use Framework\Container\Container;
use Framework\Container\ServiceProvider;

$container = new Container();
$provider = new ServiceProvider($container);

$configLoader = new ConfigLoader(BASE_DIR . '/setup/config');
$provider->config('config', $configLoader->load());

require BASE_DIR . '/setup/services.php';
