<?php

namespace App\Commands;

use Framework\Command\Command;
use Framework\Command\Input;
use Framework\Command\Output;
use Psr\Container\ContainerInterface;

class ClearLogCommand extends Command
{
    protected string $logDir;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->logDir = $container->get('config.log.dir');
    }

    protected function name(): string
    {
        return 'clear:log';
    }

    protected function description(): string
    {
        return 'clear log information';
    }

    protected function options(): array
    {
        return [
            'file' => 'the name of log file you want to clear'
        ];
    }


    public function execute(Input $input): Output
    {
        $file = $input->getOption('file');

        if (!$file) {
            throw new \InvalidArgumentException('File not specified');
        }

        $filePath = $this->logDir . '/' . $file;

        if (!in_array($file, scandir($this->logDir))) {
            throw new \InvalidArgumentException("File $filePath does not exist");
        }

        file_put_contents($filePath, '');

        return new Output("<br><f-green>File $file cleared successfully</f-green><br>");
    }
}
