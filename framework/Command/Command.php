<?php

namespace Framework\Command;

abstract class Command
{
    private string $name;
    private string $description;
    private array $options;

    public function __construct()
    {
        $this->name = $this->name();
        $this->description = $this->description();
        $this->options = $this->options();
    }

    protected function setName(): string
    {
        return static::class;
    }

    protected function description(): string
    {
        return '';
    }

    protected function options(): array
    {
        return [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroup(): ?string
    {
        $name = explode(':', $this->name);
        return isset($name[1]) ? $name[0] : null;
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
