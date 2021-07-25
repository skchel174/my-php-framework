<?php

namespace Framework\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $config = $container->get('config.log.default');

        $logger = new Logger($config['name']);

        $logger->pushHandler(new StreamHandler($config['handler']['file'], $config['handler']['level']));

        return $logger;
    }
}
