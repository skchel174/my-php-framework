<?php

namespace Framework\Renderer;

use Framework\Renderer\Interfaces\RendererInterface;

class Renderer implements RendererInterface
{
    private TemplatesManager $templatesManager;

    public function __construct(TemplatesManager $templatesManager)
    {
        $this->templatesManager = $templatesManager;
    }

    public function render(string $template, array $parameters = []): string
    {
        return $this->templatesManager->handle($template, $parameters);
    }
}
