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

namespace Sonata\Form\Bridge\Symfony\Bundle;

use Sonata\Form\Bridge\Symfony\DependencyInjection\SonataFormExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @deprecated Since version 1.4, to be removed in 2.0. Use Sonata\Form\Bridge\Symfony\SonataFormBundle instead.
 */
final class SonataFormBundle extends Bundle
{
    /**
     * @return string
     */
    public function getPath()
    {
        return __DIR__.'/..';
    }

    /**
     * @return string
     */
    protected function getContainerExtensionClass()
    {
        return SonataFormExtension::class;
    }
}
