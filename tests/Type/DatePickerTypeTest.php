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

use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        $form = new DatePickerType();

        static::assertSame(DateType::class, $form->getParent());
    }

    public function testGetName(): void
    {
        $type = new DatePickerType();

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
}
