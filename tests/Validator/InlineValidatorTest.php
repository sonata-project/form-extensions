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
use Sonata\Form\Tests\Fixtures\Bundle\Validator\FooValidatorService;
use Sonata\Form\Validator\Constraints\InlineConstraint;
use Sonata\Form\Validator\ErrorElement;
use Sonata\Form\Validator\InlineValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * @author Ahmet Akbana <ahmetakbana@gmail.com>
 */
final class InlineValidatorTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private ContainerInterface $container;

    /**
     * @var ExecutionContextInterface&MockObject
     */
    private ExecutionContextInterface $context;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);

        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->context->method('getPropertyPath')->willReturn('bar');
    }

    public function testGetErrorElement(): void
    {
        $inlineValidator = new InlineValidator($this->container);

        $inlineValidator->initialize($this->context);

        $reflectorObject = new \ReflectionObject($inlineValidator);
        $reflectedMethod = $reflectorObject->getMethod('getErrorElement');
        $reflectedMethod->setAccessible(true);

        $errorElement = $reflectedMethod->invokeArgs($inlineValidator, ['foo']);

        static::assertInstanceOf(ErrorElement::class, $errorElement);
        static::assertSame('foo', $errorElement->getSubject());
    }

    public function testValidateWithConstraintIsClosure(): void
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('foo is equal to foo');

        $constraint = new InlineConstraint([
            'method' => static function (ErrorElement $errorElement, $value): void {
                throw new ValidatorException($errorElement->getSubject().' is equal to '.$value);
            },
            'service' => '',
            'serializingWarning' => true,
        ]);

        $inlineValidator = new InlineValidator($this->container);

        $inlineValidator->initialize($this->context);

        $inlineValidator->validate('foo', $constraint);
    }

    public function testValidateWithConstraintGetServiceIsString(): void
    {
        $constraint = new InlineConstraint([
            'method' => 'fooValidatorMethod',
            'service' => 'string',
        ]);

        $this->container->expects(static::once())
            ->method('get')
            ->with('string')
            ->willReturn(new FooValidatorService());

        $inlineValidator = new InlineValidator($this->container);

        $inlineValidator->initialize($this->context);

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('foo is equal to foo');

        $inlineValidator->validate('foo', $constraint);
    }

    public function testValidateWithConstraintGetServiceIsNotString(): void
    {
        $constraint = new InlineConstraint([
            'method' => 'fooValidatorMethod',
            'service' => new FooValidatorService(),
            'serializingWarning' => true,
        ]);

        $inlineValidator = new InlineValidator($this->container);

        $inlineValidator->initialize($this->context);

        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage('foo is equal to foo');

        $inlineValidator->validate('foo', $constraint);
    }
}
