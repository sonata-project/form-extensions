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

namespace Sonata\Form\Tests;

use PHPUnit\Framework\TestCase;
use Sonata\Form\DependencyInjection\Compiler\AdapterCompilerPass;
use Sonata\Form\DependencyInjection\Compiler\StatusRendererCompilerPass;
use Sonata\Form\SonataFormBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class SonataFormBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $containerBuilder = $this->getMockBuilder(ContainerBuilder::class)
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $containerBuilder->expects($this->any())
            ->method('addCompilerPass')
            ->will($this->returnCallback(function (CompilerPassInterface $pass): void {
                if ($pass instanceof StatusRendererCompilerPass) {
                    return;
                }

                if ($pass instanceof AdapterCompilerPass) {
                    return;
                }

                $this->fail(sprintf(
                    'Compiler pass is not one of the expected types.
                    Expects "Sonata\AdminBundle\DependencyInjection\Compiler\StatusRendererCompilerPass" or
                    "Sonata\AdminBundle\DependencyInjection\Compiler\AdapterCompilerPass", but got "%s".',
                    \get_class($pass)
                ));
            }));

        $bundle = new SonataFormBundle();
        $bundle->build($containerBuilder);
    }
}
