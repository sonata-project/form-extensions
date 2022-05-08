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

namespace Sonata\Form\Tests\Type;

use Sonata\Form\Date\JavaScriptFormatConverter;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Type\DateTimeRangePickerType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DateTimeRangePickerTypeTest extends TypeTestCase
{
    public function testGetDefaultOptions(): void
    {
        $type = new DateTimeRangePickerType();

        static::assertSame('sonata_type_datetime_range_picker', $type->getBlockPrefix());

        $type->configureOptions($resolver = new OptionsResolver());

        $options = $resolver->resolve();

        static::assertSame(
            [
                'field_options' => [],
                'field_options_start' => [],
                'field_options_end' => [
                    'datepicker_options' => [
                        'useCurrent' => false,
                    ],
                ],
                'field_type' => DateTimePickerType::class,
            ],
            $options
        );
    }

    public function testSubmit(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DateTimeRangePickerType::class);

        $form->submit([
            'start' => '2018-06-03 20:02:03',
            'end' => '2018-06-03 20:02:03',
        ]);

        static::assertTrue($form->isSynchronized());
    }

    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $type = new DateTimePickerType(new JavaScriptFormatConverter(), 'en');

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
