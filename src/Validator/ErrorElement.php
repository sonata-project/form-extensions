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
 * @method self assertBic(mixed[] $options = [])
 * @method self assertBlank(mixed[] $options = [])
 * @method self assertCallback(mixed[] $options = [])
 * @method self assertCardScheme(mixed[] $options = [])
 * @method self assertChoice(mixed[] $options = [])
 * @method self assertCollection(mixed[] $options = [])
 * @method self assertCount(mixed[] $options = [])
 * @method self assertCountry(mixed[] $options = [])
 * @method self assertCurrency(mixed[] $options = [])
 * @method self assertDate(mixed[] $options = [])
 * @method self assertDateTime(mixed[] $options = [])
 * @method self assertDisableAutoMapping(mixed[] $options = [])
 * @method self assertDivisibleBy(mixed[] $options = [])
 * @method self assertEmail(mixed[] $options = [])
 * @method self assertEnableAutoMapping(mixed[] $options = [])
 * @method self assertEqualTo(mixed[] $options = [])
 * @method self assertExpression(mixed[] $options = [])
 * @method self assertFile(mixed[] $options = [])
 * @method self assertGreaterThan(mixed[] $options = [])
 * @method self assertGreaterThanOrEqual(mixed[] $options = [])
 * @method self assertIban(mixed[] $options = [])
 * @method self assertIdenticalTo(mixed[] $options = [])
 * @method self assertImage(mixed[] $options = [])
 * @method self assertIp(mixed[] $options = [])
 * @method self assertIsbn(mixed[] $options = [])
 * @method self assertIsFalse(mixed[] $options = [])
 * @method self assertIsNull(mixed[] $options = [])
 * @method self assertIssn(mixed[] $options = [])
 * @method self assertIsTrue(mixed[] $options = [])
 * @method self assertJson(mixed[] $options = [])
 * @method self assertLanguage(mixed[] $options = [])
 * @method self assertLength(mixed[] $options = [])
 * @method self assertLessThan(mixed[] $options = [])
 * @method self assertLessThanOrEqual(mixed[] $options = [])
 * @method self assertLocale(mixed[] $options = [])
 * @method self assertLuhn(mixed[] $options = [])
 * @method self assertNegative(mixed[] $options = [])
 * @method self assertNegativeOrZero(mixed[] $options = [])
 * @method self assertNotBlank(mixed[] $options = [])
 * @method self assertNotCompromisedPassword(mixed[] $options = [])
 * @method self assertNotEqualTo(mixed[] $options = [])
 * @method self assertNotIdentificalTo(mixed[] $options = [])
 * @method self assertNotNull(mixed[] $options = [])
 * @method self assertPositive(mixed[] $options = [])
 * @method self assertPositiveOrZero(mixed[] $options = [])
 * @method self assertRange(mixed[] $options = [])
 * @method self assertRegex(mixed[] $options = [])
 * @method self assertTime(mixed[] $options = [])
 * @method self assertTimezone(mixed[] $options = [])
 * @method self assertTraverse(mixed[] $options = [])
 * @method self assertType(mixed[] $options = [])
 * @method self assertUnique(mixed[] $options = [])
 * @method self assertUrl(mixed[] $options = [])
 * @method self assertUuid(mixed[] $options = [])
 * @method self assertValid(mixed[] $options = [])
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
        private ?string $group,
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
        /*
         * Existing code was
         * $key = $key ? $name.'.'.$key : $name;
         *
         * There is certainly a bug here or we should deprecate the key param.
         */
        $this->stack[] = $key ? $name.'.1' : $name;

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
            return \sprintf('%s.%s', $this->basePropertyPath, (string) $propertyPath);
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
                throw new \RuntimeException(\sprintf(
                    'Cannot find the class "%s".',
                    $className
                ));
            }
        }

        if (!is_a($className, Constraint::class, true)) {
            throw new \RuntimeException(\sprintf(
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
