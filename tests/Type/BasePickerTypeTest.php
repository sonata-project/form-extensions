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

use PHPUnit\Framework\TestCase;
use Sonata\Form\Date\JavaScriptFormatConverter;
use Sonata\Form\Tests\Fixtures\Type\DummyPickerType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class BasePickerTypeTest extends TestCase
{
    private JavaScriptFormatConverter $javaScriptFormatConverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->javaScriptFormatConverter = new JavaScriptFormatConverter();
    }

    /**
     * @param array<string, mixed> $expectedOptions
     * @param array<string, mixed> $options
     *
     * @dataProvider provideTypeOptions
     */
    public function testFinishView(array $expectedOptions, array $options): void
    {
        $type = new DummyPickerType(
            $this->javaScriptFormatConverter,
            'en',
        );

        $view = new FormView();
        $form = new Form($this->createStub(FormConfigInterface::class));

        $type->finishView($view, $form, $options);

        static::assertArrayHasKey('datepicker_options', $view->vars);
        static::assertArrayHasKey('datepicker_use_button', $view->vars);
        static::assertSame($expectedOptions, $view->vars['datepicker_options']);
    }

    public function testChangeLocale(): void
    {
        $type = new DummyPickerType(
            $this->javaScriptFormatConverter,
            'en',
        );

        static::assertSame('en', $type->getLocale());

        $type->setLocale('fr');

        static::assertSame('fr', $type->getLocale());
    }

    /**
     * @return iterable<array{array<string, mixed>, array<string, mixed>}>
     */
    public function provideTypeOptions(): iterable
    {
        yield [
            [
                'constraints' => [
                    'minDate' => '1/1/1900',
                    'maxDate' => '0:00',
                ],
                'localization' => [
                    'format' => 'H:mm',
                ],
            ],
            [
                'format' => 'H:mm',
                'datepicker_options' => [
                    'constraints' => [
                        'minDate' => '1/1/1900',
                        'maxDate' => '0:00',
                    ],
                ],
            ],
        ];

        yield [
            [
                'display' => [
                    'components' => [
                        'seconds' => true,
                    ],
                ],
                'localization' => [
                    'format' => 'yyyy-MM-dd',
                ],
            ],
            [
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'datepicker_options' => [
                    'display' => [
                        'components' => [
                            'seconds' => true,
                        ],
                    ],
                ],
            ],
        ];

        yield [
            [
                'localization' => [
                    'format' => '',
                ],
            ],
            [
                'datepicker_options' => [
                    'display' => [
                        'components' => [],
                        'icons' => [],
                        'buttons' => [],
                    ],
                    'restrictions' => [],
                ],
            ],
        ];
    }
}
