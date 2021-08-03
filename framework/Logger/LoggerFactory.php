<?php

namespace Framework\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $config = $container->get('config.log');

        $name = $config['default']['name'];
        $path = $config['dir'] . $config['default']['file'];
        $level = $config['default']['level'];

        $logger = new Logger($name);

        $logger->pushHandler(new StreamHandler($path, $level));

        return $logger;
    }
}
