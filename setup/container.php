<?php

use Framework\Container\Container;
use Framework\Container\ServiceProviderFactory;

$container = Container::getInstance();

(new ServiceProviderFactory)($container);

return $container;
