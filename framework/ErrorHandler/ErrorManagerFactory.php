<?php

namespace Framework\ErrorHandler;

use Framework\ErrorHandler\Interfaces\ErrorManagerInterface;
use Psr\Container\ContainerInterface;

class ErrorManagerFactory
{
    const HANDLERS_FILE = BASE_DIR . '/setup/error-handlers.php';

    public function __invoke(ContainerInterface $container): ErrorManagerInterface
    {
        $collection = $container->get(HandlersCollection::class);
        $this->handlers($collection);
        return new ErrorManager(
            $collection,
            $container->get(Debugger::class),
            $container->get('config.debug'),
        );
    }

    protected function handlers(HandlersCollection $collection): void
    {
        require static::HANDLERS_FILE;
    }
}
