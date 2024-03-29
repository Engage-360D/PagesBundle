<?php

/**
 * This file is part of the Engage360d package bundles.
 *
 */

namespace Engage360d\Bundle\PagesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class Engage360dPagesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load("entities.yml");
        $loader->load("events.yml");
        $loader->load("form/page.yml");
        $loader->load("manager/page.yml");
        $loader->load("form/category.yml");
        $loader->load("manager/category.yml");
        $loader->load("form/menu.yml");
        $loader->load("manager/menu.yml");
        $loader->load("services.yml");
    }
}
