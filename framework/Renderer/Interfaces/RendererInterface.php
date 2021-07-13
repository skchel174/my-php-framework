<?php

namespace Framework\Renderer\Interfaces;

interface RendererInterface
{
    public function render(string $template, array $parameters = []): string;
}
