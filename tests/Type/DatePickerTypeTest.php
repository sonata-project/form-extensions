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

use PHPUnit\Framework\MockObject\MockObject;
use Sonata\Form\Date\MomentFormatConverter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
class DatePickerTypeTest extends TypeTestCase
{
    public function testParentIsDateType(): void
    {
        $form = new DatePickerType(
            $this->createMock(MomentFormatConverter::class),
            $this->getTranslatorMock(),
            $this->getRequestStack()
        );

        $this->assertSame(DateType::class, $form->getParent());
    }

    public function testGetName(): void
    {
        $type = new DatePickerType(new MomentFormatConverter(), $this->getTranslatorMock(), $this->getRequestStack());

        $this->assertSame('sonata_type_date_picker', $type->getBlockPrefix());
    }

    public function testSubmitValidData(): void
    {
        \Locale::setDefault('en');
        $form = $this->factory->create(DatePickerType::class, new \DateTime('2018-06-03'), [
            'format' => \IntlDateFormatter::LONG,
            'html5' => false,
        ]);

        $this->assertSame('June 3, 2018', $form->getViewData());
        $form->submit('June 5, 2018');
        $this->assertSame('2018-06-05', $form->getData()->format('Y-m-d'));
        $this->assertTrue($form->isSynchronized());
    }

    protected function getExtensions()
    {
        $type = new DatePickerType(new MomentFormatConverter(), $this->getTranslatorMock(), $this->getRequestStack());

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @return MockObject|TranslatorInterface|LegacyTranslatorInterface\
     */
    private function getTranslatorMock(): MockObject
    {
        if (interface_exists(TranslatorInterface::class)) {
            return $this->createMock(TranslatorInterface::class);
        }

        $translator = $this->createMock(LegacyTranslatorInterface::class);
        $translator->method('getLocale')->willReturn('en');

        return $translator;
    }

    private function getRequestStack(): RequestStack
    {
        $requestStack = new RequestStack();
        $request = $this->createMock(Request::class);
        $request
            ->method('getLocale')
            ->willReturn('en');
        $requestStack->push($request);

        return $requestStack;
    }
}
