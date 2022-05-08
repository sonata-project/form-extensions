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

use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\Form\Type\DateRangeType;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Type\DateTimeRangePickerType;
use Sonata\Form\Type\DateTimeRangeType;
use Sonata\Form\Type\ImmutableArrayType;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->services()

        ->set('sonata.form.type.array', ImmutableArrayType::class)
            ->tag('form.type', ['alias' => 'sonata_type_immutable_array'])

        ->set('sonata.form.type.boolean', BooleanType::class)
            ->tag('form.type', ['alias' => 'sonata_type_boolean'])

        ->set('sonata.form.type.collection', CollectionType::class)
            ->tag('form.type', ['alias' => 'sonata_type_collection'])

        ->set('sonata.form.type.date_range', DateRangeType::class)
            ->tag('form.type', ['alias' => 'sonata_type_date_range'])

        ->set('sonata.form.type.datetime_range', DateTimeRangeType::class)
            ->tag('form.type', ['alias' => 'sonata_type_datetime_range'])

        ->set('sonata.form.type.date_picker', DatePickerType::class)
            ->tag('kernel.locale_aware')
            ->tag('form.type', ['alias' => 'sonata_type_date_picker'])
            ->args([
                service('sonata.form.date.javascript_format_converter'),
                param('kernel.default_locale'),
            ])

        ->set('sonata.form.type.datetime_picker', DateTimePickerType::class)
            ->tag('kernel.locale_aware')
            ->tag('form.type', ['alias' => 'sonata_type_datetime_picker'])
            ->args([
                service('sonata.form.date.javascript_format_converter'),
                param('kernel.default_locale'),
            ])

        ->set('sonata.form.type.date_range_picker', DateRangePickerType::class)
            ->tag('form.type', ['alias' => 'sonata_type_date_range_picker'])

        ->set('sonata.form.type.datetime_range_picker', DateTimeRangePickerType::class)
            ->tag('form.type', ['alias' => 'sonata_type_datetime_range_picker']);
};
