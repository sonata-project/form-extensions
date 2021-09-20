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

use PHPUnit\Framework\TestCase;
use Sonata\Form\Validator\Constraints\InlineConstraint;

/**
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
class InlineConstraintTest extends TestCase
{
    public function testValidatedBy(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertSame('sonata.form.validator.inline', $constraint->validatedBy());
    }

    public function testIsClosure(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertFalse($constraint->isClosure());

        $constraint = new InlineConstraint(['service' => 'foo', 'method' => static function (): void {
        }, 'serializingWarning' => true]);
        static::assertTrue($constraint->isClosure());
    }

    public function testGetClosure(): void
    {
        $closure = static function (): string {
            return 'FOO';
        };

        $constraint = new InlineConstraint(['service' => 'foo', 'method' => $closure, 'serializingWarning' => true]);
        static::assertSame($closure, $constraint->getClosure());
    }

    public function testGetTargets(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertSame(InlineConstraint::CLASS_CONSTRAINT, $constraint->getTargets());
    }

    public function testGetRequiredOptions(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertSame(['service', 'method'], $constraint->getRequiredOptions());
    }

    public function testGetMethod(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertSame('bar', $constraint->getMethod());
    }

    public function testGetService(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);
        static::assertSame('foo', $constraint->getService());
    }

    public function testClosureSerialization(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => static function (): void {
        }, 'serializingWarning' => true]);

        $expected = 'O:50:"Sonata\Form\Validator\Constraints\InlineConstraint":0:{}';

        static::assertSame($expected, serialize($constraint));

        $constraint = unserialize($expected);

        static::assertInstanceOf('Closure', $constraint->getMethod());
        static::assertEmpty($constraint->getService());
        static::assertTrue($constraint->getSerializingWarning());
    }

    public function testStandardSerialization(): void
    {
        $constraint = new InlineConstraint(['service' => 'foo', 'method' => 'bar']);

        $data = serialize($constraint);

        $constraint = unserialize($data);

        static::assertSame($constraint->getService(), 'foo');
        static::assertSame($constraint->getMethod(), 'bar');
        static::assertNull($constraint->getSerializingWarning());
    }

    public function testSerializingWarningIsFalseWithServiceIsNotString(): void
    {
        $this->expectException(
            'RuntimeException'
        );
        $this->expectExceptionMessage(
            'You are using a closure with the `InlineConstraint`, this constraint'.
            ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
            ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.'
        );

        new InlineConstraint(['service' => 1, 'method' => 'foo', 'serializingWarning' => false]);
    }

    public function testSerializingWarningIsFalseWithMethodIsNotString(): void
    {
        $this->expectException(
            'RuntimeException'
        );
        $this->expectExceptionMessage(
            'You are using a closure with the `InlineConstraint`, this constraint'.
            ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
            ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.'
        );

        new InlineConstraint(['service' => 'foo', 'method' => 1, 'serializingWarning' => false]);
    }
}
