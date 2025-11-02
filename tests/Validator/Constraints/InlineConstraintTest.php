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

namespace Sonata\Form\Test\Validator\Constraints;

use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use PHPUnit\Framework\TestCase;
use Sonata\Form\Validator\Constraints\InlineConstraint;

/**
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
final class InlineConstraintTest extends TestCase
{
    #[IgnoreDeprecations]
    public function testInstantiateWithDeprecatedOptionsArray(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar', 'groups' => ['foo']]);
        static::assertSame('foo', $constraint->getService());
        static::assertSame('bar', $constraint->getMethod());
        static::assertSame(['foo'], $constraint->groups);
    }

    #[IgnoreDeprecations]
    public function testInstantiateWithDeprecatedNamedOptionsArray(): void
    {
        $constraint = new InlineConstraint(options: ['service' => 'foo', 'method' => 'bar', 'groups' => ['foo']]);
        static::assertSame('foo', $constraint->getService());
        static::assertSame('bar', $constraint->getMethod());
        static::assertSame(['foo'], $constraint->groups);
    }

    public function testInstantiateWithNamedArgumentOptions(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar', serializingWarning: true, groups: ['foo']);
        static::assertSame('foo', $constraint->getService());
        static::assertSame('bar', $constraint->getMethod());
        static::assertSame(['foo'], $constraint->groups);
    }

    public function testValidatedBy(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar');
        static::assertSame('sonata.form.validator.inline', $constraint->validatedBy());
    }

    public function testIsClosure(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar');
        static::assertFalse($constraint->isClosure());

        $constraint = new InlineConstraint(service: 'foo', method: static function (): void {
        }, serializingWarning: true);
        static::assertTrue($constraint->isClosure());
    }

    public function testGetClosure(): void
    {
        $closure = static fn (): string => 'FOO';

        $constraint = new InlineConstraint(service: 'foo', method: $closure, serializingWarning: true);
        static::assertSame($closure, $constraint->getClosure());
    }

    public function testGetTargets(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar');
        static::assertSame(InlineConstraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }

    public function testGetMethod(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar');
        static::assertSame('bar', $constraint->getMethod());
    }

    public function testGetService(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar');
        static::assertSame('foo', $constraint->getService());
    }

    public function testClosureSerialization(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: static function (): void {
        }, serializingWarning: true);

        $expected = 'O:50:"Sonata\Form\Validator\Constraints\InlineConstraint":0:{}';

        static::assertSame($expected, serialize($constraint));

        $constraint = unserialize($expected);

        static::assertInstanceOf(\Closure::class, $constraint->getMethod());
        static::assertEmpty($constraint->getService());
        static::assertTrue($constraint->getSerializingWarning());

        // serialize again gives the same
        static::assertSame($expected, serialize($constraint));
    }

    public function testStandardSerialization(): void
    {
        $constraint = new InlineConstraint(service: 'foo', method: 'bar', groups: ['foo']);

        $data = serialize($constraint);

        /** @var InlineConstraint $constraint */
        $constraint = unserialize($data);

        static::assertSame('foo', $constraint->getService());
        static::assertSame('bar', $constraint->getMethod());
        static::assertSame(['foo'], $constraint->groups);
        static::assertFalse($constraint->getSerializingWarning());
    }

    public function testSerializingWarningIsFalseWithServiceIsNotString(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'You are using a closure with the `InlineConstraint`, this constraint'.
            ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
            ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.'
        );

        new InlineConstraint(service: 1, method: 'foo', serializingWarning: false);
    }

    public function testSerializingWarningIsFalseWithMethodIsNotString(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'You are using a closure with the `InlineConstraint`, this constraint'.
            ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
            ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.'
        );

        new InlineConstraint(service: 'foo', method: 1, serializingWarning: false);
    }
}
