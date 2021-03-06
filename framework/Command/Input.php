<?php

namespace Framework\Command;

class Input
{
    private string $command;
    private array $options;

    public function __construct(array $argv = [])
    {
        $this->command = $argv[1] ?? '';
        $this->options = $this->parseOptions(array_slice($argv, 2));
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name): ?string
    {
        return $this->options[$name] ?? null;
    }

    protected function parseOptions(array $data): array
    {
        $options = [];
        foreach ($data as $option) {
            $value = null;
            if (str_contains($option, '=')) {
                [$option, $value] = explode('=', $option);
            }
            $options[$option] = $value;
        }
        return $options;
    }
}
