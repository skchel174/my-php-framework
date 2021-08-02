<?php

namespace Framework\Command;

abstract class Command
{
    private string $name;
    private string $description;
    private array $options;

    public function __construct()
    {
        $this->name();
        $this->description();
        $this->options();
    }

    protected function name(): void
    {
        $this->name = static::class;
    }

    protected function description(): void
    {
        $this->description = '';
    }

    protected function options(): void
    {
        $this->options = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    abstract public function execute(Input $input): Output;
}
