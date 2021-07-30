<?php

namespace Framework\Http\Sessions;

use Framework\Container\Interfaces\ContainerInterface;
use Framework\Http\Sessions\Interfaces\SessionInterface;

class SessionFactory
{
    public function __invoke(ContainerInterface $container): SessionInterface
    {
        $config = $container->get('config.sessions');
        $this->setSessionSaveHandler($config['session_save_handler']);
        return new Session($config['options']);
    }

    protected function setSessionSaveHandler(array $config): void {}
}
