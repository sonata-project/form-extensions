<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Form\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SonataFormExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig('sonata_admin');

        foreach ($configs as $config) {
            if (isset($config['options']['form_type'])) {
                $container->prependExtensionConfig(
                    $this->getAlias(),
                    ['form_type' => $config['options']['form_type']]
                );
            }
        }
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('flash.xml');
        $loader->load('form_types.xml');
        $loader->load('validator.xml');

        $this->registerFlashTypes($container, $config);
        $container->setParameter('sonata.form.type', $config['form_type']);
    }

    /**
     * Registers flash message types defined in configuration to flash manager.
     */
    public function registerFlashTypes(ContainerBuilder $container, array $config): void
    {
        $mergedConfig = array_merge_recursive($config['flashmessage'], [
            'success' => ['types' => [
                'success' => ['domain' => 'SonataFormBundle'],
                'sonata_flash_success' => ['domain' => 'SonataAdminBundle'],
                'sonata_user_success' => ['domain' => 'SonataUserBundle'],
                'fos_user_success' => ['domain' => 'FOSUserBundle'],
            ]],
            'warning' => ['types' => [
                'warning' => ['domain' => 'SonataFormBundle'],
                'sonata_flash_info' => ['domain' => 'SonataAdminBundle'],
            ]],
            'danger' => ['types' => [
                'error' => ['domain' => 'SonataFormBundle'],
                'sonata_flash_error' => ['domain' => 'SonataAdminBundle'],
                'sonata_user_error' => ['domain' => 'SonataUserBundle'],
            ]],
        ]);

        $types = $cssClasses = [];

        foreach ($mergedConfig as $typeKey => $typeConfig) {
            $types[$typeKey] = $typeConfig['types'];
            $cssClasses[$typeKey] = array_key_exists('css_class', $typeConfig) ? $typeConfig['css_class'] : $typeKey;
        }

        $identifier = 'sonata.form.flashmessage.manager';

        $definition = $container->getDefinition($identifier);
        $definition->replaceArgument(2, $types);
        $definition->replaceArgument(3, $cssClasses);

        $container->setDefinition($identifier, $definition);
    }
}
