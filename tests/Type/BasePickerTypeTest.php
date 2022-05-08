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
use Sonata\Form\Tests\Fixtures\Type\DummyPickerType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class BasePickerTypeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param array<string, mixed> $expectedOptions
     * @param array<string, mixed> $options
     *
     * @dataProvider provideTypeOptions
     */
    public function testFinishView(array $expectedOptions, array $options): void
    {
        $type = new DummyPickerType();

        $view = new FormView();
        $form = new Form($this->createStub(FormConfigInterface::class));

        $type->finishView($view, $form, $options);

        static::assertArrayHasKey('dp_options', $view->vars);
        static::assertArrayHasKey('datepicker_use_button', $view->vars);
        static::assertSame($expectedOptions['minDate'], $view->vars['dp_options']['minDate']);
        static::assertSame($expectedOptions['maxDate'], $view->vars['dp_options']['maxDate']);

        if (true === $expectedOptions['useSeconds']) {
            static::assertTrue($view->vars['dp_options']['useSeconds']);
        } elseif (false === $expectedOptions['useSeconds']) {
            static::assertFalse($view->vars['dp_options']['useSeconds']);
        }

        foreach ($view->vars['dp_options'] as $dpKey => $dpValue) {
            static::assertFalse(strpos($dpKey, '_'));
            static::assertFalse(strpos($dpKey, 'dp_'));
        }

        static::assertSame('text', $view->vars['type']);
    }

    /**
     * @param array<string, mixed> $expectedOptions
     * @param array<string, mixed> $options
     *
     * @dataProvider provideTypeOptions
     */
    public function testTimePickerIntlFormater(array $expectedOptions, array $options): void
    {
        $type = new DummyPickerType();

        $view = new FormView();
        $form = new Form($this->createStub(FormConfigInterface::class));

        $type->finishView($view, $form, $options);

        if (true === $expectedOptions['useSeconds']) {
            static::assertTrue($view->vars['dp_options']['useSeconds']);
        } elseif (false === $expectedOptions['useSeconds']) {
            static::assertFalse($view->vars['dp_options']['useSeconds']);
        }

        static::assertSame($expectedOptions['maxDate'], $view->vars['dp_options']['maxDate']);
    }

    /**
     * @return iterable<array{array<string, mixed>, array<string, mixed>}>
     */
    public function provideTypeOptions(): iterable
    {
        yield [
            [
                'minDate' => '1/1/1900',
                'maxDate' => '0:00',
                'useSeconds' => false,
            ],
            [
                'format' => 'H:mm',
                'dp_min_date' => '1/1/1900',
                'dp_max_date' => new \DateTime('3/1/2001'),
                'dp_pick_time' => true,
                'dp_pick_date' => false,
            ],
        ];

        yield [
            [
                'minDate' => '1/1/1900',
                'maxDate' => '2001-01-01',
                'useSeconds' => false,
            ],
            [
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'dp_min_date' => '1/1/1900',
                'dp_max_date' => new \DateTime('1/1/2001'),
                'dp_use_seconds' => true,
            ],
        ];

        yield [
            [
                'minDate' => '1/1/1900',
                'maxDate' => '0:00',
                'useSeconds' => false,
            ],
            [
                'format' => 'H:mm',
                'dp_min_date' => '1/1/1900',
                'dp_max_date' => new \DateTimeImmutable('7/10/2016'),
                'dp_pick_time' => true,
                'dp_pick_date' => false,
            ],
        ];
    }
}
