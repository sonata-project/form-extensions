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

use Sonata\Form\Type\ImmutableArrayType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\FormBuilderInterface as TestFormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ImmutableArrayTypeTest extends TypeTestCase
{
    public function testGetDefaultOptions(): void
    {
        $type = new ImmutableArrayType();

        static::assertSame('sonata_type_immutable_array', $type->getBlockPrefix());

        static::assertSame(FormType::class, $type->getParent());

        $type->configureOptions($resolver = new OptionsResolver());

        $options = $resolver->resolve();

        $expected = [
            'keys' => [],
        ];

        static::assertSame($expected, $options);
    }

    public function testCallback(): void
    {
        $type = new ImmutableArrayType();

        $builder = $this->createMock(TestFormBuilderInterface::class);
        $builder->expects(static::once())->method('add')->with(
            static::callback(static function ($name): bool {
                return 'ttl' === $name;
            }),
            static::callback(static function ($name): bool {
                return TextType::class === $name;
            }),
            static::callback(static function ($name): bool {
                return $name === [1 => '1'];
            })
        );

        $optionsCallback = static function ($builder, $name, $type, $extra): array {
            static::assertSame(['foo', 'bar'], $extra);
            static::assertSame($name, 'ttl');
            static::assertSame($type, TextType::class);
            static::assertInstanceOf(TestFormBuilderInterface::class, $builder);

            return ['1' => '1'];
        };

        $options = [
            'keys' => [
                ['ttl', TextType::class, $optionsCallback, 'foo', 'bar'],
            ],
        ];

        $type->buildForm($builder, $options);
    }

    public function testWithIncompleteOptions(): void
    {
        $optionsResolver = new OptionsResolver();

        $type = new ImmutableArrayType();
        $type->configureOptions($optionsResolver);

        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage(
            'The option "keys" with value array is invalid.'
        );

        $optionsResolver->resolve(['keys' => [['test']]]);
    }

    public function testFormBuilderIsAValidElement(): void
    {
        $optionsResolver = new OptionsResolver();

        $type = new ImmutableArrayType();
        $type->configureOptions($optionsResolver);

        static::assertArrayHasKey(
            'keys',
            $optionsResolver->resolve(['keys' => [$this->createMock(
                FormBuilderInterface::class
            )]])
        );
    }
}
