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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sonata\Form\Twig\CanonicalizeRuntime;
use Sonata\Form\Twig\Extension\CanonicalizeExtension;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set('sonata.form.twig.canonicalize_extension', CanonicalizeExtension::class)
            ->tag('twig.extension')

        ->set('sonata.form.twig.canonicalize_runtime', CanonicalizeRuntime::class)
            ->tag('twig.runtime')
            ->args([
                service('request_stack'),
            ]);
};
