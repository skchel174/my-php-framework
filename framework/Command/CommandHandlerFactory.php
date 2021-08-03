<?php

namespace Framework\Command;

use Framework\Command\Commands\HelpCommand;
use Psr\Container\ContainerInterface;

class CommandHandlerFactory
{
    const COMMANDS_FILE = BASE_DIR . '/setup/commands.php';

    public function __invoke(ContainerInterface $container): CommandHandler
    {
        $commands = $this->resolveCommands($container);
        $collection = $this->makeCollection($commands);
        return new CommandHandler($collection);
    }

    protected function makeCollection(array $commands): CommandsCollection
    {
        $collection = new CommandsCollection();

        foreach ($commands as $command) {
            $collection->add($command->getName(), $command);
        }

        $collection->sort();

        $help = new HelpCommand($collection);
        $collection->add($help->getName(), $help);

        return $collection;
    }

    protected function resolveCommands(ContainerInterface $container): array
    {
        $commands = require static::COMMANDS_FILE;
        return array_map(function(string|object $command) use ($container) {
            return is_string($command) ? $container->get($command) : $command;
        }, $commands);
    }
}
