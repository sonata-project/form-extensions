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

namespace Sonata\Form\Tests\Validator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sonata\Form\Tests\Fixtures\Bundle\Entity\Foo;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
final class ErrorElementTest extends TestCase
{
    private ErrorElement $errorElement;

    /**
     * @var ExecutionContextInterface&MockObject
     */
    private ExecutionContextInterface $context;

    /**
     * @var ContextualValidatorInterface&MockObject
     */
    private ContextualValidatorInterface $contextualValidator;

    private Foo $subject;

    protected function setUp(): void
    {
        $constraintValidatorFactory = $this->createMock(ConstraintValidatorFactoryInterface::class);

        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->context->expects(static::once())
                ->method('getPropertyPath')
                ->willReturn('bar');

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder
            ->method(static::anything())
            ->willReturnSelf();

        $this->context
            ->method('buildViolation')
            ->willReturn($builder);

        $validator = $this->createMock(ValidatorInterface::class);

        $this->contextualValidator = $this->createMock(ContextualValidatorInterface::class);
        $this->contextualValidator
            ->method(static::anything())
            ->willReturnSelf();
        $validator
            ->method('inContext')
            ->willReturn($this->contextualValidator);

        $this->context
            ->method('getValidator')
            ->willReturn($validator);

        $this->subject = new Foo();

        $this->errorElement = new ErrorElement($this->subject, $constraintValidatorFactory, $this->context, 'foo_core');
    }

    public function testGetSubject(): void
    {
        static::assertSame($this->subject, $this->errorElement->getSubject());
    }

    public function testGetErrorsEmpty(): void
    {
        static::assertSame([], $this->errorElement->getErrors());
    }

    public function testGetErrors(): void
    {
        $this->errorElement->addViolation('Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR');
        static::assertSame([['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR']], $this->errorElement->getErrors());
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testAddViolation(): void
    {
        $this->errorElement->addViolation(['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR']);
        static::assertSame([['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR']], $this->errorElement->getErrors());
    }

    /**
     * NEXT_MAJOR: Remove this test.
     *
     * @group legacy
     */
    public function testAddViolationWithTranslationDomain(): void
    {
        $this->errorElement->addViolation(['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR'], [], null, 'translation_domain');
        static::assertSame([['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR']], $this->errorElement->getErrors());
    }

    public function testAddConstraint(): void
    {
        $constraint = new NotNull();
        $this->contextualValidator->expects(static::once())
            ->method('atPath')
            ->with('');
        $this->contextualValidator->expects(static::once())
            ->method('validate')
            ->with($this->subject, $constraint, 'foo_core');

        $this->errorElement->addConstraint($constraint);
    }

    public function testWith(): void
    {
        $constraint = new NotNull();

        $this->contextualValidator->expects(static::once())
            ->method('atPath')
            ->with('bar');
        $this->contextualValidator->expects(static::once())
            ->method('validate')
            ->with(null, $constraint, 'foo_core');

        $this->errorElement->with('bar');
        $this->errorElement->addConstraint($constraint);
        $this->errorElement->end();
    }

    public function testCall(): void
    {
        $constraint = new NotNull();

        $this->contextualValidator->expects(static::once())
            ->method('atPath')
            ->with('bar');
        $this->contextualValidator->expects(static::once())
            ->method('validate')
            ->with(null, $constraint, 'foo_core');

        $this->errorElement->with('bar');
        $this->errorElement->assertNotNull();
        $this->errorElement->end();
    }

    /**
     * @psalm-suppress UndefinedMagicMethod
     */
    public function testCallException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to recognize the command');

        $this->errorElement->with('bar');
        // @phpstan-ignore-next-line
        $this->errorElement->baz();
    }

    public function testGetFullPropertyPath(): void
    {
        $this->errorElement->with('baz');
        static::assertSame('bar.baz', $this->errorElement->getFullPropertyPath());
        $this->errorElement->end();

        static::assertSame('bar', $this->errorElement->getFullPropertyPath());
    }

    public function testFluidInterface(): void
    {
        $constraint = new NotNull();

        $this->contextualValidator
            ->method('atPath')
            ->with('');
        $this->contextualValidator
            ->method('validate')
            ->with($this->subject, $constraint, 'foo_core');

        static::assertSame($this->errorElement, $this->errorElement->with('baz'));
        static::assertSame($this->errorElement, $this->errorElement->end());
        static::assertSame($this->errorElement, $this->errorElement->addViolation('Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR'));
        static::assertSame($this->errorElement, $this->errorElement->addConstraint($constraint));
        static::assertSame($this->errorElement, $this->errorElement->assertNotNull());
    }
}
