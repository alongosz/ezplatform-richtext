<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\Tests\EzPlatformRichTextBundle\SetupFactory;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension;
use EzSystems\EzPlatformRichTextBundle\DependencyInjection\EzPlatformRichTextExtension;
use EzSystems\IntegrationTests\EzPlatformRichText\eZ\API\SetupFactory\LegacySetupFactory;
use JMS\TranslationBundle\DependencyInjection\JMSTranslationExtension;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BundleSetupFactory extends LegacySetupFactory
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     *
     * @throws \Exception
     */
    protected function externalBuildContainer(ContainerBuilder $containerBuilder)
    {
        parent::externalBuildContainer($containerBuilder);

        $loader = new YamlFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        (new FrameworkExtension())->load([], $containerBuilder);
        (new JMSTranslationExtension())->load([], $containerBuilder);
        (new EzPublishCoreExtension())->load([], $containerBuilder);

        $extension = new EzPlatformRichTextExtension();
        $extension->load([], $containerBuilder);
    }
}
