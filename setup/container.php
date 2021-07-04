<?php

use Framework\Container\ConfigLoader;
use Framework\Container\Container;
use Framework\Container\ServiceProvider;

$container = Container::getInstance();
$provider = $container->get(ServiceProvider::class);

$configLoader = new ConfigLoader(BASE_DIR . '/setup/config');
$provider->config('config', $configLoader->load());

require BASE_DIR . '/setup/services/services.php';

return $container;
