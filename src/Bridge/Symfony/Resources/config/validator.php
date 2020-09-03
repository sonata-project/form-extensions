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

use Sonata\Form\Validator\InlineValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()
        // Use "service" function for creating references to services when dropping support for Symfony 4.4
        ->set('sonata.form.validator.inline', InlineValidator::class)
            ->tag('validator.constraint_validator', [
                'alias' => 'sonata.form.validator.inline',
            ])
            ->args([
                new ReferenceConfigurator('service_container'),
                new ReferenceConfigurator('validator.validator_factory'),
            ]);
};
