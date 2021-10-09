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

use Sonata\Form\Type\EqualType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EqualTypeTest extends TypeTestCase
{
    /**
     * @group legacy
     */
    public function testParentIsChoiceType(): void
    {
        $form = new EqualType();

        static::assertSame(ChoiceType::class, $form->getParent());
    }

    /**
     * @group legacy
     */
    public function testGetDefaultOptions(): void
    {
        $type = new EqualType();

        static::assertSame('sonata_type_equal', $type->getBlockPrefix());
        static::assertSame(ChoiceType::class, $type->getParent());

        $type->configureOptions($resolver = new OptionsResolver());

        $options = $resolver->resolve();

        $expected = [
            'choice_translation_domain' => 'SonataFormBundle',
            'choices' => ['label_type_equals' => 1, 'label_type_not_equals' => 2],
        ];

        static::assertSame($expected, $options);
    }
}
