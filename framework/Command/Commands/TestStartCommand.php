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
            'filter' => 'filter which tests to run',
            'suite' => 'run concrete testsuite tests',
            'group' => 'run tests from the specified group',
        ];
    }

    public function execute(Input $input): Output
    {
        $options = $this->makeCommandOptions($input);
        echo `vendor/bin/phpunit$options`;
        return new Output('');
    }

    protected function makeCommandOptions(Input $input): string
    {
        $options = '';
        foreach (array_keys($this->getOptions()) as $option) {
            $optionSetter = 'set' . ucfirst($option);
            $options .= $this->$optionSetter($input);
        }
        return $options;
    }

    protected function setFilter(Input $input): string
    {
        $result = '';
        if ($testsuite = $input->getOption('filter')) {
            $result = ' --filter ' . $testsuite;
        }
        return $result;
    }

    protected function setSuite(Input $input): string
    {
        $result = '';
        if ($testsuite = $input->getOption('suite')) {
            $result = ' --testsuite ' . $testsuite;
        }
        return $result;
    }

    protected function setGroup(Input $input): string
    {
        $result = '';
        if ($testsuite = $input->getOption('group')) {
            $result = ' --group ' . $testsuite;
        }
        return $result;
    }
}
