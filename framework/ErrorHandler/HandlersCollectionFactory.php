<?php

namespace Framework\ErrorHandler;

use Framework\Container\Interfaces\ContainerInterface;

class HandlersCollectionFactory
{
    const HANDLERS_FILE = BASE_DIR . '/setup/error-handlers.php';

    public function __invoke(ContainerInterface $container): HandlersCollection
    {
        $collection = new HandlersCollection($container);
        require static::HANDLERS_FILE;
        return $collection;
    }
}
