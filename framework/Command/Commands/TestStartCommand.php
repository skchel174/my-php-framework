<?php


namespace Framework\Command\Commands;

use Framework\Command\Command;
use Framework\Command\Input;
use Framework\Command\Output;

class TestStartCommand extends Command
{
    protected function name(): string
    {
        return 'test';
    }

    protected function description(): string
    {
        return 'starting development local server';
    }

    protected function options(): array
    {
        return [
            'port' => 'port to start local server',
        ];
    }

    public function execute(Input $input): Output
    {

    }
}
