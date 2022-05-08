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

namespace Sonata\Form\Twig;

use Twig\Extension\RuntimeExtensionInterface;

/**
 * NEXT_MAJOR: Remove this class.
 *
 * @deprecated since sonata-project/form-extensions 2.x, to be removed on 3.0.
 */
final class CanonicalizeRuntime implements RuntimeExtensionInterface
{
    /**
     * We return null instead of removing the extension to be compatible with SonataAdminBundle 4.x.
     */
    public function getCanonicalizedLocaleForMoment(): ?string
    {
        return null;
    }
}
