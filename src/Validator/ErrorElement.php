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

namespace Sonata\Form\Validator;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method self assertBic(array $options = [])
 * @method self assertBlank(array $options = [])
 * @method self assertCallback(array $options = [])
 * @method self assertCardScheme(array $options = [])
 * @method self assertChoice(array $options = [])
 * @method self assertCollection(array $options = [])
 * @method self assertCount(array $options = [])
 * @method self assertCountry(array $options = [])
 * @method self assertCurrency(array $options = [])
 * @method self assertDate(array $options = [])
 * @method self assertDateTime(array $options = [])
 * @method self assertDisableAutoMapping(array $options = [])
 * @method self assertDivisibleBy(array $options = [])
 * @method self assertEmail(array $options = [])
 * @method self assertEnableAutoMapping(array $options = [])
 * @method self assertEqualTo(array $options = [])
 * @method self assertExpression(array $options = [])
 * @method self assertFile(array $options = [])
 * @method self assertGreaterThan(array $options = [])
 * @method self assertGreaterThanOrEqual(array $options = [])
 * @method self assertIban(array $options = [])
 * @method self assertIdenticalTo(array $options = [])
 * @method self assertImage(array $options = [])
 * @method self assertIp(array $options = [])
 * @method self assertIsbn(array $options = [])
 * @method self assertIsFalse(array $options = [])
 * @method self assertIsNull(array $options = [])
 * @method self assertIssn(array $options = [])
 * @method self assertIsTrue(array $options = [])
 * @method self assertJson(array $options = [])
 * @method self assertLanguage(array $options = [])
 * @method self assertLength(array $options = [])
 * @method self assertLessThan(array $options = [])
 * @method self assertLessThanOrEqual(array $options = [])
 * @method self assertLocale(array $options = [])
 * @method self assertLuhn(array $options = [])
 * @method self assertNegative(array $options = [])
 * @method self assertNegativeOrZero(array $options = [])
 * @method self assertNotBlank(array $options = [])
 * @method self assertNotCompromisedPassword(array $options = [])
 * @method self assertNotEqualTo(array $options = [])
 * @method self assertNotIdentificalTo(array $options = [])
 * @method self assertNotNull(array $options = [])
 * @method self assertPositive(array $options = [])
 * @method self assertPositiveOrZero(array $options = [])
 * @method self assertRange(array $options = [])
 * @method self assertRegex(array $options = [])
 * @method self assertTime(array $options = [])
 * @method self assertTimezone(array $options = [])
 * @method self assertTraverse(array $options = [])
 * @method self assertType(array $options = [])
 * @method self assertUnique(array $options = [])
 * @method self assertUrl(array $options = [])
 * @method self assertUuid(array $options = [])
 * @method self assertValid(array $options = [])
 */
final class ErrorElement
{
    private const DEFAULT_TRANSLATION_DOMAIN = 'validators';

    /**
     * @var string[]
     */
    private array $stack = [];

    /**
     * @var PropertyPathInterface[]
     */
    private array $propertyPaths = [];

    private string $current = '';

    private string $basePropertyPath;

    /**
     * @var array<array{string, array<string, mixed>, mixed}>
     */
    private array $errors = [];

    public function __construct(
        private mixed $subject,
        private ExecutionContextInterface $context,
        private ?string $group
    ) {
        $this->basePropertyPath = $this->context->getPropertyPath();
    }

    /**
     * @param mixed[] $arguments
     *
     * @throws \RuntimeException
     */
    public function __call(string $name, array $arguments = []): self
    {
        if (str_starts_with($name, 'assert')) {
            $this->validate($this->newConstraint(substr($name, 6), $arguments[0] ?? []));
        } else {
            throw new \RuntimeException('Unable to recognize the command');
        }

        return $this;
    }

    public function addConstraint(Constraint $constraint): self
    {
        $this->validate($constraint);

        return $this;
    }

    public function with(string $name, bool $key = false): self
    {
        $key = $key ? $name.'.'.$key : $name;
        $this->stack[] = $key;

        $this->current = implode('.', $this->stack);

        if (!isset($this->propertyPaths[$this->current])) {
            $this->propertyPaths[$this->current] = new PropertyPath($this->current);
        }

        return $this;
    }

    public function end(): self
    {
        array_pop($this->stack);

        $this->current = implode('.', $this->stack);

        return $this;
    }

    public function getFullPropertyPath(): string
    {
        $propertyPath = $this->getCurrentPropertyPath();
        if (null !== $propertyPath) {
            return sprintf('%s.%s', $this->basePropertyPath, (string) $propertyPath);
        }

        return $this->basePropertyPath;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return $this
     */
    public function addViolation(string $message, array $parameters = [], mixed $value = null, string $translationDomain = self::DEFAULT_TRANSLATION_DOMAIN): self
    {
        $subPath = (string) $this->getCurrentPropertyPath();

        $this->context->buildViolation($message)
            ->atPath($subPath)
            ->setParameters($parameters)
            ->setTranslationDomain($translationDomain)
            ->setInvalidValue($value)
            ->addViolation();

        $this->errors[] = [$message, $parameters, $value];

        return $this;
    }

    /**
     * @return array<array{string, array<string, mixed>, mixed}>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validate(Constraint $constraint): void
    {
        $this->context->getValidator()
            ->inContext($this->context)
            ->atPath((string) $this->getCurrentPropertyPath())
            ->validate($this->getValue(), $constraint, $this->group);
    }

    /**
     * Return the value linked to.
     */
    private function getValue(): mixed
    {
        if ('' === $this->current) {
            return $this->subject;
        }

        $propertyPath = $this->getCurrentPropertyPath();
        \assert(null !== $propertyPath);

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($this->subject, $propertyPath);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws \RuntimeException
     *
     * @psalm-suppress UnsafeInstantiation -- it is supposed that Constraint constructor is not going to change
     */
    private function newConstraint(string $name, array $options = []): Constraint
    {
        if (str_contains($name, '\\') && class_exists($name)) {
            $className = $name;
        } else {
            $className = 'Symfony\\Component\\Validator\\Constraints\\'.$name;
            if (!class_exists($className)) {
                throw new \RuntimeException(sprintf(
                    'Cannot find the class "%s".',
                    $className
                ));
            }
        }

        if (!is_a($className, Constraint::class, true)) {
            throw new \RuntimeException(sprintf(
                'The class "%s" MUST implement "%s".',
                $className,
                Constraint::class
            ));
        }

        return new $className($options);
    }

    private function getCurrentPropertyPath(): ?PropertyPathInterface
    {
        if (!isset($this->propertyPaths[$this->current])) {
            return null; // global error
        }

        return $this->propertyPaths[$this->current];
    }
}
