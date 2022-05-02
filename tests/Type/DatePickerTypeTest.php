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
use Sonata\Form\Date\MomentFormatConverter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
final class DatePickerTypeTest extends TypeTestCase
{
    /**
     * @var Stub&TranslatorInterface
     */
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->translator = $this->createStub(TranslatorInterface::class);

        parent::setUp();
    }

    public function testParentIsDateType(): void
    {
        $form = new DatePickerType(
            $this->createMock(MomentFormatConverter::class),
            $this->translator,
            'en'
        );

        static::assertSame(DateType::class, $form->getParent());
    }

    public function testGetName(): void
    {
        $type = new DatePickerType(new MomentFormatConverter(), $this->translator, 'en');

        static::assertSame('sonata_type_date_picker', $type->getBlockPrefix());
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

    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions(): array
    {
        $type = new DatePickerType(new MomentFormatConverter(), $this->translator, 'en');

        return [
            new PreloadedExtension([$type], []),
        ];
    }
}
