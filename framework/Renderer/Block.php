<?php

namespace Framework\Renderer;

use Framework\Renderer\Exceptions\RenderClosedBlockException;

class Block
{
    protected string $name;
    protected bool $closed = false;
    protected string $content = '';

    public function __construct(string $name)
    {
        $this->name = $name;
        ob_start();
    }

    public function close(): void
    {
        if ($this->closed) {
            throw new RenderClosedBlockException($this->name);
        }

        $this->closed = true;

        $content = ob_get_clean();
        $this->content($content);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function isEmpty(): bool
    {
        return empty($this->content);
    }

    public function content(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
