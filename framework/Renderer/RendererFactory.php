<?php

namespace Framework\Renderer;

use Framework\Http\Router\Interfaces\RouteDispatcherInterface;
use Framework\Renderer\Extensions\AssetsPathExtension;
use Framework\Renderer\Extensions\CSRFTokenExtension;
use Framework\Renderer\Extensions\MethodSpecifierExtension;
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
        $extensions->setExtension($container->get(RouteDispatcherInterface::class), 'route');
        $extensions->setExtension($container->get(AssetsPathExtension::class), 'assets');
        $extensions->setExtension($container->get(MethodSpecifierExtension::class), 'method');
        $extensions->setExtension($container->get(CSRFTokenExtension::class), 'csrf');
    }
}
