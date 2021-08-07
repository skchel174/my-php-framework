<?php

namespace Framework\Command\Commands;

use Framework\Command\Command;
use Framework\Command\Input;
use Framework\Command\Output;

class ServerStartCommand extends Command
{
    protected function name(): string
    {
        return 'serve';
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
        $port = $input->getOption('port') ?? 8000;
        exec('php -S 127.0.0.1:' . $port . ' server.php');
        return new Output('');
    }
}
