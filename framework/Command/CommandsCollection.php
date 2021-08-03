<?php

namespace Framework\Command;

use Framework\Command\Exceptions\CommandNotExistException;

class CommandsCollection
{
    private array $commands = [];

    public function has(string $name): bool
    {
        return array_key_exists($name, $this->commands);
    }

    public function get(string $name): Command
    {
        $this->commandExistGuard($name);
        return $this->commands[$name];
    }

    public function getAll(): array
    {
        return $this->commands;
    }

    public function getGroup(string $name): array
    {
        $group = explode(':', $name)[0];
        return array_filter($this->commands, function ($command) use ($group) {
           /** @var Command $command */
           return $command->getGroup() === $group;
        });
    }

    public function add(string $name, Command $command): void
    {
        $this->commands[$name] = $command;
    }

    public function sort(): void
    {
        ksort($this->commands);
    }

    protected function commandExistGuard(string $name): void
    {
        if (!$this->has($name)) {
            throw new CommandNotExistException();
        }
    }
}
