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

    public function __construct(string $directory, FiltersCollection $filters, ExtensionsCollection $extensions)
    {
        $this->directory = $directory;
        $this->filters = $filters;
        $this->extensions = $extensions;
        $this->blocks = new BlocksCollection();
    }

    public function handle(string $template, array $parameters = []): string
    {
        $this->currentTemplate = $this->createTemplate($template);

        try {
            while ($this->currentTemplate) {
                $content = $this->renderTemplate($this->currentTemplate, $parameters);
            }

            if ($this->blocks->isNotClosedExist()) {
                throw new NotClosedBlockException($this->blocks->getOpen());
            }
        } catch (\Throwable $e) {
            ob_get_clean();
            throw $e;
        }

        return $content;
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

    public function getFilters(): FiltersCollection
    {
        return $this->filters;
    }

    public function getExtensions(): ExtensionsCollection
    {
        return $this->extensions;
    }

    protected function createTemplate(string $template): Template
    {
        $this->templateExistGuard($template);
        return new Template($this->directory . '/' . $template, $this);
    }

    protected function renderTemplate(Template $template, array $parameters): string
    {
        $this->currentTemplate = null;
        return $template->render($parameters);
    }

    protected function templateExistGuard(string $template)
    {
        $path = $this->directory . '/' . $template;
        if (!file_exists($path)) {
            throw new InvalidTemplateFileException($path);
        }
    }
}
