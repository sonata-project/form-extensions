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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author Alexander <iam.asm89@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sonata_form');
        $rootNode = $treeBuilder->getRootNode();

        $this->addFlashMessageSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Returns configuration for flash messages.
     *
     * @psalm-suppress UndefinedInterfaceMethod
     *
     * @see https://github.com/psalm/psalm-plugin-symfony/issues/174
     */
    private function addFlashMessageSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('form_type')
                    ->defaultValue('standard')
                    ->validate()
                    ->ifNotInArray($validFormTypes = ['standard', 'horizontal'])
                        ->thenInvalid(sprintf(
                            'The form_type option value must be one of %s',
                            $validFormTypesString = implode(', ', $validFormTypes)
                        ))
                    ->end()
                    ->info(sprintf('Must be one of %s', $validFormTypesString))
                ->end()
            ->end();
    }
}
