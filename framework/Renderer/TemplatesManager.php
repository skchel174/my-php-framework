<?php

namespace Framework\Renderer;

use Framework\Renderer\Exceptions\InvalidTemplateFileException;
use Framework\Renderer\Exceptions\NotClosedBlockException;
use Framework\Renderer\Exceptions\NotOpenedBlockException;

class TemplatesManager
{
    private string $directory;
    private FiltersCollection $filters;
    private ExtensionsCollection $extensions;
    private ?Template $currentTemplate = null;
    private BlocksCollection $blocks;

    public function __construct(
        string $directory,
        FiltersCollection $filters,
        ExtensionsCollection $extensions,
        BlocksCollection $blocks,
    )
    {
        $this->directory = $directory;
        $this->filters = $filters;
        $this->extensions = $extensions;
        $this->blocks = $blocks;
    }

    public function handle(string $template, array $parameters = []): string
    {
        try {
            $this->currentTemplate = $this->createTemplate($template);

            while ($this->currentTemplate) {
                $content = $this->renderTemplate($this->currentTemplate, $parameters);
            }

            if ($this->blocks->isNotClosedExist()) {
                throw new NotClosedBlockException($this->blocks->getOpen());
            }

            return $content;
        } catch (\Throwable $e) {
            ob_get_clean();
            throw $e;
        }
    }

    public function openBlock(string $name): void
    {
        $this->blocks->setOpen(new Block($name));
    }

    public function closeBlock(): string
    {
        $block = $this->blocks->getOpen();
        if ($block === null) {
            throw new NotOpenedBlockException();
        }

        $block->close();

        if ($this->blocks->has($block)) {
            $previousBlock = $this->blocks->get($block->getName());
            $block->content($previousBlock->getContent());
        } else {
            $this->blocks->set($block);
        }
        return $block->getContent();
    }

    public function includeTemplate(string $template, array $parameters): string
    {
        $template = $this->createTemplate($template);
        return $template->render($parameters);
    }

    public function extendTemplate(string $template)
    {
        $this->currentTemplate = $this->createTemplate($template);
    }

    public function createTemplate(string $template): Template
    {
        $this->templateExistGuard($template);
        return new Template($this->directory . '/' . $template, $this);
    }

    public function renderTemplate(Template $template, array $parameters): string
    {
        $this->currentTemplate = null;
        return $template->render($parameters);
    }

    public function filter(mixed $parameter, array|string $filters): mixed
    {
        return $this->filters->handle($parameter, $filters);
    }

    public function callExtension(string $name, array $arguments): mixed
    {
        return $this->extensions->call($name, $arguments);
    }

    protected function templateExistGuard(string $template)
    {
        $path = $this->directory . '/' . $template;
        if (!file_exists($path)) {
            throw new InvalidTemplateFileException($path);
        }
    }
}
