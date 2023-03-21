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

namespace Sonata\Form\Tests\Bridge\Symfony\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sonata\Form\Bridge\Symfony\DependencyInjection\SonataFormExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class SonataFormExtensionTest extends AbstractExtensionTestCase
{
    public function testAfterLoadingTheWrappingParameterIsSet(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load();
        $this->assertContainerBuilderHasParameter(
            'sonata.form.form_type'
        );
        static::assertSame(
            'standard',
            $this->container->getParameter(
                'sonata.form.form_type'
            )
        );
    }

    public function testHorizontalFormTypeMeansNoWrapping(): void
    {
        $this->container->setParameter('kernel.bundles', []);
        $this->load([
            'form_type' => 'horizontal',
        ]);
        $this->assertContainerBuilderHasParameter(
            'sonata.form.form_type'
        );
        static::assertSame(
            'horizontal',
            $this->container->getParameter(
                'sonata.form.form_type'
            )
        );
    }

    public function testPrepend(): void
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->registerExtension(new class() extends Extension {
            public function load(array $configs, ContainerBuilder $container): void
            {
            }

            public function getAlias(): string
            {
                return 'sonata_admin';
            }
        });
        $containerBuilder->loadFromExtension('sonata_admin', [
            'some_key_we_do_not_care_about' => 42,
            'options' => ['form_type' => 'standard'],
        ]);
        $containerBuilder->loadFromExtension('sonata_admin', [
            'options' => ['form_type' => 'horizontal'],
        ]);

        static::assertSame([], $containerBuilder->getExtensionConfig('sonata_form'));

        $extension = new SonataFormExtension();
        $extension->prepend($containerBuilder);

        static::assertSame([
            ['form_type' => 'horizontal'],
            ['form_type' => 'standard'],
        ], $containerBuilder->getExtensionConfig('sonata_form'));
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SonataFormExtension(),
        ];
    }
}
