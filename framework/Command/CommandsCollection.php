<?php

namespace Framework\Command;

class CommandsCollection
{
    private array $commands;

    public function __construct(array $commands = [])
    {
        $this->commands = $commands;
    }

    public function get(string $name): ?Command
    {
        return $this->commands[$name] ?? null;
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
           return explode(':', $command->getName())[0] === $group;
        });
    }

    public function add(string $name, Command $command): void
    {
        $this->commands[$name] = $command;
    }
}
