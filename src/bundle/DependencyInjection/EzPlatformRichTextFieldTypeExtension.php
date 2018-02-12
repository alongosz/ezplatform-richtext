<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRichTextFieldTypeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

/**
 * eZ Platform RichText Field Type Bundle extension.
 */
class EzPlatformRichTextFieldTypeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Load eZ Platform RichText Field Type Bundle configuration.
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->prependCustomTagsCoreConfiguration($container);
    }

    /**
     * Prepend Custom Tags Core Configuration.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function prependCustomTagsCoreConfiguration(ContainerBuilder $container)
    {
        $customTagsConfig = Yaml::parseFile(
            __DIR__ . '/../Resources/config/extension/custom_tags.yml'
        );
        $container->prependExtensionConfig('ezpublish', $customTagsConfig);
    }
}
