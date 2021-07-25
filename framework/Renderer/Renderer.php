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
        try {
            return $this->templatesManager->handle($template, $parameters);
        } catch (\Throwable $e) {
            ob_get_clean();
            throw $e;
        }
    }
}
