<?php

namespace Framework\Renderer;

class BlocksCollection
{
    private array $blocks = [];
    private \SplStack $openBlocks;

    public function __construct()
    {
        $this->openBlocks = new \SplStack();
    }

    public function setOpen(Block $block): void
    {
        $this->openBlocks->push($block);
    }

    public function getOpen(): ?Block
    {
        if (!$this->openBlocks->isEmpty()) {
            return null;
        }
        return $this->openBlocks->pop();
    }

    public function isNotClosedExist(): bool
    {
        return !$this->openBlocks->isEmpty();
    }

    public function set(Block $block): void
    {
        $this->blocks[$block->getName()] = $block;
    }

    public function get(string $name): ?Block
    {
        return $this->blocks[$name] ?? null;
    }

    public function has(Block $block): bool
    {
        return array_key_exists($block->getName(), $this->blocks);
    }
}
