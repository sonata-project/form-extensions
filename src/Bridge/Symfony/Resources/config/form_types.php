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

use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\Form\Type\DateRangeType;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Type\DateTimeRangePickerType;
use Sonata\Form\Type\DateTimeRangeType;
use Sonata\Form\Type\EqualType;
use Sonata\Form\Type\ImmutableArrayType;
use Symfony\Component\Config\Definition\BaseNode;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // BC layer for deprecation messages for symfony/config < 5.1
    $getDeprecationParameters = static function (string $message, string $version): array {
        if (method_exists(BaseNode::class, 'getDeprecation')) {
            return [
                'sonata-project/form-extensions',
                $version,
                $message,
            ];
        }

        return [$message];
    };

    // Use "service" function for creating references to services when dropping support for Symfony 4.4
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $containerConfigurator->services()

        ->set('sonata.form.type.array', ImmutableArrayType::class)
            ->tag('form.type', ['alias' => 'sonata_type_immutable_array'])

        ->set('sonata.form.type.boolean', BooleanType::class)
            ->tag('form.type', ['alias' => 'sonata_type_boolean'])

        ->set('sonata.form.type.collection', CollectionType::class)
            ->tag('form.type', ['alias' => 'sonata_type_collection'])

        ->set('sonata.form.type.date_range', DateRangeType::class)
            ->tag('form.type', ['alias' => 'sonata_type_date_range'])
            ->args([
                new ReferenceConfigurator('translator'),
            ])

        ->set('sonata.form.type.datetime_range', DateTimeRangeType::class)
            ->tag('form.type', ['alias' => 'sonata_type_datetime_range'])
            ->args([
                new ReferenceConfigurator('translator'),
            ])

        ->set('sonata.form.type.date_picker', DatePickerType::class)
            ->tag('kernel.locale_aware')
            ->tag('form.type', ['alias' => 'sonata_type_date_picker'])
            ->args([
                new ReferenceConfigurator('sonata.form.date.moment_format_converter'),
                new ReferenceConfigurator('translator'),
                '%kernel.default_locale%',
            ])

        ->set('sonata.form.type.datetime_picker', DateTimePickerType::class)
            ->tag('kernel.locale_aware')
            ->tag('form.type', ['alias' => 'sonata_type_datetime_picker'])
            ->args([
                new ReferenceConfigurator('sonata.form.date.moment_format_converter'),
                new ReferenceConfigurator('translator'),
                '%kernel.default_locale%',
            ])

        ->set('sonata.form.type.date_range_picker', DateRangePickerType::class)
            ->tag('form.type', ['alias' => 'sonata_type_date_range_picker'])
            ->args([
                new ReferenceConfigurator('translator'),
            ])

        ->set('sonata.form.type.datetime_range_picker', DateTimeRangePickerType::class)
            ->tag('form.type', ['alias' => 'sonata_type_datetime_range_picker'])
            ->args([
                new ReferenceConfigurator('translator'),
            ])

        ->set('sonata.form.type.equal', EqualType::class)
            ->tag('form.type', ['alias' => 'sonata_type_equal'])
            ->deprecate(...$getDeprecationParameters(
                'The "%service_id%" service is deprecated since sonata-project/form-extensions 1.2 and will be removed in 2.0. Use Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType instead',
                '1.2'
            ))
            ->args([
                new ReferenceConfigurator('translator'),
            ]);
};
