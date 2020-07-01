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

use Sonata\Form\Bridge\Symfony\SonataFormBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

@trigger_error(sprintf(
    'The %s\SonataFormBundle class is deprecated since version 1.4, to be removed in 2.0. Use %s instead.',
    __NAMESPACE__,
    SonataFormBundle::class
), E_USER_DEPRECATED);

if (false) {
    /**
     * NEXT_MAJOR: remove this class.
     *
     * @deprecated Since version 1.4, to be removed in 2.0. Use Sonata\Form\Bridge\Symfony\SonataFormBundle instead.
     */
    final class SonataFormBundle extends Bundle
    {
    }
}

class_alias(SonataFormBundle::class, __NAMESPACE__.'\SonataFormBundle');
