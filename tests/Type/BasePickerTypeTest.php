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

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sonata\Form\Date\MomentFormatConverter;
use Sonata\Form\Tests\Fixtures\Type\DummyPickerType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
class BasePickerTypeTest extends TestCase
{
    /**
     * @var Stub&TranslatorInterface
     */
    private $translator;

    /**
     * @var MomentFormatConverter
     */
    private $momentFormatConverter;

    protected function setUp(): void
    {
        $this->momentFormatConverter = new MomentFormatConverter();
        $this->translator = $this->createStub(TranslatorInterface::class);
    }

    public function testFinishView(): void
    {
        $type = new DummyPickerType(
            $this->momentFormatConverter,
            $this->translator,
            'en'
        );

        $view = new FormView();
        $form = new Form($this->createMock(FormConfigInterface::class));

        $type->finishView($view, $form, [
            'html5' => false,
            'format' => 'yyyy-MM-dd',
            'dp_min_date' => '1/1/1900',
            'dp_max_date' => new \DateTime('1/1/2001'),
            'dp_use_seconds' => true,
        ]);

        $this->assertArrayHasKey('moment_format', $view->vars);
        $this->assertArrayHasKey('dp_options', $view->vars);
        $this->assertArrayHasKey('datepicker_use_button', $view->vars);
        $this->assertFalse($view->vars['dp_options']['useSeconds']);
        $this->assertSame('1/1/1900', $view->vars['dp_options']['minDate']);
        $this->assertSame('2001-01-01', $view->vars['dp_options']['maxDate']);

        foreach ($view->vars['dp_options'] as $dpKey => $dpValue) {
            $this->assertFalse(strpos($dpKey, '_'));
            $this->assertFalse(strpos($dpKey, 'dp_'));
        }

        $this->assertSame('text', $view->vars['type']);
    }

    public function testTimePickerIntlFormater(): void
    {
        $type = new DummyPickerType(
            $this->momentFormatConverter,
            $this->translator,
            'en'
        );

        $view = new FormView();
        $form = new Form($this->createMock(FormConfigInterface::class));

        $type->finishView($view, $form, [
            'format' => 'H:mm',
            'dp_min_date' => '1/1/1900',
            'dp_max_date' => new \DateTime('3/1/2001'),
            'dp_pick_time' => true,
            'dp_pick_date' => false,
        ]);

        $this->assertFalse($view->vars['dp_options']['useSeconds']);
        $this->assertSame('H:mm', $view->vars['moment_format']);
        $this->assertSame('0:00', $view->vars['dp_options']['maxDate']);
    }

    public function testTimePickerUsesDefaultLocaleWithoutRequest(): void
    {
        $type = new DummyPickerType(
            $this->momentFormatConverter,
            $this->translator,
            'en'
        );

        $this->assertSame('en', $type->getLocale());
    }

    /**
     * @group legacy
     */
    public function testConstructWithRequestStack(): void
    {
        $type = new DummyPickerType(
            $this->momentFormatConverter,
            $this->translator,
            $this->getRequestStack()
        );

        $this->assertSame('en', $type->getLocale());
    }

    private function getRequestStack(string $locale = 'en'): RequestStack
    {
        $requestStack = new RequestStack();
        $request = $this->createMock(Request::class);
        $request
            ->method('getLocale')
            ->willReturn($locale);
        $requestStack->push($request);

        return $requestStack;
    }
}
