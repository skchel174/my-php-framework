<?php

namespace Framework\ErrorHandler;

use Framework\Container\Interfaces\ContainerInterface;

class HandlersCollectionFactory
{
    public function __invoke(ContainerInterface $container): HandlersCollection
    {
        $collection = $container->get(HandlersCollection::class);
        require BASE_DIR . '/setup/error-handlers.php';
    }
}
