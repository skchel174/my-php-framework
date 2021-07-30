<?php

namespace Framework\Renderer;

use Framework\Http\Router\RouteDispatcher;
use Framework\Renderer\Extensions\AssetsDispatcher;
use Framework\Renderer\Extensions\CSRFToken;
use Framework\Renderer\Interfaces\RendererInterface;
use Psr\Container\ContainerInterface;

class RendererFactory
{
    public function __invoke(ContainerInterface $container): RendererInterface
    {
        $extensions = $container->get(ExtensionsCollection::class);
        $this->extensions($container, $extensions);

        $templatesManager = new TemplatesManager(
            $container->get('config.templates'),
            $container->get(BlocksCollection::class),
            $container->get(FiltersCollection::class),
            $extensions,
        );

        return new Renderer($templatesManager);
    }

    protected function extensions(ContainerInterface $container, ExtensionsCollection $extensions): void
    {
        $extensions->setExtension($container->get(AssetsDispatcher::class), 'assets');
        $extensions->setExtension($container->get(RouteDispatcher::class), 'route');
        $extensions->setExtension($container->get(CSRFToken::class), 'csrf');
    }
}
