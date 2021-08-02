<?php

namespace Framework\Command;

use Framework\Command\Exceptions\CommandNotExistException;

class CommandHandler
{
    private CommandsCollection $commands;

    public function __construct(CommandsCollection $commands)
    {
        $this->commands = $commands;
    }

    public function run(Input $input): Output
    {
        try {
            if (!$command = $this->commands->get($input->getCommand())) {
                throw new CommandNotExistException($input->getCommand());
            }
            return $command->execute($input);
        } catch (\Throwable $e) {
            return $this->errorOutput($e);
        }
    }

    protected function errorOutput(\Throwable $e): Output
    {
        $file = $e->getFile();
        $line = $e->getLine();
        $message = $e->getMessage();
        return new Output("<bg-red>$message [$file: $line]</bg-red><br>");
    }
}
