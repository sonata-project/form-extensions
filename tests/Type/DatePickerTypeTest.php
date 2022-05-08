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
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class DatePickerTypeTest extends TypeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testParentIsDateType(): void
    {
        $form = new DatePickerType(
            new JavaScriptFormatConverter(),
            'en',
        );

        static::assertSame(DateType::class, $form->getParent());
    }

    public function testGetName(): void
    {
        $type = new DatePickerType(
            new JavaScriptFormatConverter(),
            'en',
        );

        static::assertSame('sonata_type_datetime_picker', $type->getBlockPrefix());
    }

    public function testSubmitValidData(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DatePickerType::class, new \DateTime('2018-06-03'), [
            'format' => \IntlDateFormatter::LONG,
            'html5' => false,
        ]);

        static::assertSame('June 3, 2018', $form->getViewData());
        $form->submit('June 5, 2018');
        static::assertSame('2018-06-05', $form->getData()->format('Y-m-d'));
        static::assertTrue($form->isSynchronized());
    }

    public function testDateConversion(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DatePickerType::class, new \DateTime('2018-06-03'), [
            'format' => 'yyyy-MM-dd',
            'html5' => false,
            'datepicker_options' => [
                'restrictions' => [
                    'minDate' => new \DateTime('2018-06-01'),
                    'disabledDates' => [new \DateTime('2018-06-02')],
                ],
            ],
        ]);

        static::assertSame('2018-06-03', $form->getViewData());
        $form->submit('2018-06-05');
        static::assertSame('2018-06-05', $form->getData()->format('Y-m-d'));
        static::assertTrue($form->isSynchronized());
    }

    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $type = new DatePickerType(new JavaScriptFormatConverter(), 'en');

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
