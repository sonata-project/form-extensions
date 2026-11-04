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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class DateTimePickerTypeTest extends TypeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testParentIsDateTimeType(): void
    {
        $form = new DateTimePickerType(
            new JavaScriptFormatConverter(),
            'en'
        );

        static::assertSame(DateTimeType::class, $form->getParent());
    }

    public function testGetName(): void
    {
        $type = new DateTimePickerType(
            new JavaScriptFormatConverter(),
            'en'
        );

        static::assertSame('sonata_type_datetime_picker', $type->getBlockPrefix());
    }

    public function testSubmitUnmatchingDateFormat(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DateTimePickerType::class, new \DateTime('2018-06-03 20:02:03'), [
            'format' => \IntlDateFormatter::NONE,
            'datepicker_options' => [
                'display' => [
                    'components' => [
                        'calendar' => false,
                        'seconds' => true,
                    ],
                ],
            ],
            'html5' => false,
        ]);

        $form->submit('05:23');
        static::assertFalse($form->isSynchronized());
    }

    public function testSubmitMatchingDateFormat(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DateTimePickerType::class, new \DateTime('2018-06-03 20:02:03'), [
            'format' => \IntlDateFormatter::NONE,
            'datepicker_options' => [
                'display' => [
                    'components' => [
                        'calendar' => false,
                        'seconds' => false,
                    ],
                ],
            ],
            'html5' => false,
        ]);

        static::assertSame("8:02\u{202F}PM", $form->getViewData());

        $form->submit("5:23\u{202F}AM");
        static::assertSame('1970-01-01 05:23:00', $form->getData()->format('Y-m-d H:i:s'));
        static::assertTrue($form->isSynchronized());
    }

    public function testHtml5FormatWithoutSeconds(): void
    {
        $form = $this->factory->create(DateTimePickerType::class, new \DateTime('2018-06-03 20:02:03'), [
            'format' => DateTimeType::HTML5_FORMAT,
        ]);
        $view = $form->createView();

        static::assertSame('2018-06-03T20:02', $form->getViewData());
        static::assertSame('yyyy-MM-dd[T]HH:mm', $view->vars['datepicker_options']['localization']['format']);
        static::assertFalse($view->vars['datepicker_options']['display']['components']['seconds']);

        $form->submit('2018-06-05T05:23');
        static::assertSame('2018-06-05 05:23:00', $form->getData()->format('Y-m-d H:i:s'));
        static::assertTrue($form->isSynchronized());
    }

    public function testHtml5FormatWithSeconds(): void
    {
        $form = $this->factory->create(DateTimePickerType::class, new \DateTime('2018-06-03 20:02:03'), [
            'format' => DateTimeType::HTML5_FORMAT,
            'with_seconds' => true,
        ]);
        $view = $form->createView();

        static::assertSame('2018-06-03T20:02:03', $form->getViewData());
        static::assertSame('yyyy-MM-dd[T]HH:mm:ss', $view->vars['datepicker_options']['localization']['format']);
        static::assertTrue($view->vars['datepicker_options']['display']['components']['seconds']);

        $form->submit('2018-06-05T05:23:45');
        static::assertSame('2018-06-05 05:23:45', $form->getData()->format('Y-m-d H:i:s'));
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
