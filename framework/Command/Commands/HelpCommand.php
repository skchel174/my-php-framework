<?php

namespace Framework\Command\Commands;

use Framework\Command\Command;
use Framework\Command\CommandsCollection;
use Framework\Command\Exceptions\CommandNotExistException;
use Framework\Command\Input;
use Framework\Command\Output;

class HelpCommand extends Command
{
    private CommandsCollection $commands;

    public function __construct(CommandsCollection $commands)
    {
        parent::__construct();
        $this->commands = $commands;
    }

    protected function name(): string
    {
        return 'help';
    }

    protected function description(): string
    {
        return 'displays information about console commands';
    }

    protected function options(): array
    {
        return [
            'name' => 'command name',
        ];
    }

    /**
     * 1. help (выводит все группы команд)
     * 2. help name=clear (выводит группу команд с единым префиксом)
     * 3. help name=clear:log (выводит параметры команды)
     */
    public function execute(Input $input): Output
    {
        if (!$name = $input->getOption('name')) {
            return $this->getAllCommands();
        }

        if ($this->commands->has($name)) {
            $command = $this->commands->get($name);
            return $this->getCommandOptions($command);
        }

        if ($group = $this->commands->getGroup($name)) {
            return $this->getGroupCommands($name, $group);
        }

        throw new CommandNotExistException();
    }

    protected function getAllCommands(): Output
    {
        $response = '';
        $currentGroup = '';

        foreach ($this->commands->getAll() as $command) {
            /** @var Command $command */
            $group = $command->getGroup();
            if ($group && $group !== $currentGroup) {
                $currentGroup = $group;
                $response .= "<br><f-yellow>[ $group ]</f-yellow>";
            }
            $response .= "<br><f-green>{$command->getName()}</f-green> - " . $command->getDescription() . '<br>';
        }

        return new Output($response);
    }

    protected function getCommandOptions(Command $command): Output
    {
        $response = "<br><f-yellow>[ {$command->getName()} ]</f-yellow><br>";

        foreach ($command->getOptions() as $option => $description) {
            $response .= "<f-green>$option</f-green> - " . $description . "<br>";
        }

        return new Output($response);
    }

    protected function getGroupCommands(string $name, array $group): Output
    {
        $response = "<br><f-yellow>[ $name ]</f-yellow><br>";

        foreach ($group as $command) {
            /** @var Command $command */
            $response .= "<f-green>{$command->getName()}</f-green> - " . $command->getDescription() . "<br>";
        }

        return new Output($response);
    }
}
