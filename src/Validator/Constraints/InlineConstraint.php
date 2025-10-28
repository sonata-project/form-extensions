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

namespace Sonata\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Constraint which allows inline-validation inside services.
 *
 * @Annotation
 *
 * @Target({"CLASS"})
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class InlineConstraint extends Constraint
{
    protected mixed $service = null;

    /**
     * @param array<string, mixed>|null $options
     */
    public function __construct(
        mixed $service = null,
        protected mixed $method = null,
        protected bool $serializingWarning = false,
        ?array $groups = null,
        ?array $options = null,
    ) {
        if (\is_array($service) || \is_array($options)) {
            trigger_deprecation(
                'sonata-project/form-extensions',
                '2.6.0',
                'Passing an array of options to configure the "%s" constraint is deprecated. Use named arguments instead.',
                self::class,
            );

            $options ??= [];
            if (!\is_array($service)) {
                $this->service = $service;
            } else {
                $options = array_merge($options, $service);
            }
            parent::__construct($options, groups: $groups);
        } else {
            $this->service = $service;
            parent::__construct(groups: $groups);
        }

        if (null === $this->service || null === $this->method) {
            throw new MissingOptionsException(
                \sprintf('The required options/arguments "service" and "method" must be set for constraint "%s"', self::class),
                ['service', 'method'],
            );
        }

        if ((!\is_string($this->service) || !\is_string($this->method)) && true !== $this->serializingWarning) {
            throw new \RuntimeException('You are using a closure with the `InlineConstraint`, this constraint'.
                ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
                ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.');
        }
    }

    public function __sleep(): array
    {
        // @phpstan-ignore-next-line to initialize "groups" option if it is not set
        $this->groups;

        if (!\is_string($this->service) || !\is_string($this->method)) {
            return [];
        }

        return array_keys(get_object_vars($this));
    }

    public function __serialize(): array
    {
        if (!\is_string($this->service) || !\is_string($this->method)) {
            return [];
        }

        return get_object_vars($this);
    }

    public function __wakeup(): void
    {
        if (\is_string($this->service) && \is_string($this->method)) {
            return;
        }

        $this->method = static function (): void {
        };

        $this->serializingWarning = true;
    }

    public function validatedBy(): string
    {
        return 'sonata.form.validator.inline';
    }

    public function isClosure(): bool
    {
        return $this->method instanceof \Closure;
    }

    public function getClosure(): mixed
    {
        return $this->method;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function getMethod(): mixed
    {
        return $this->method;
    }

    public function getService(): mixed
    {
        return $this->service;
    }

    public function getSerializingWarning(): bool
    {
        return $this->serializingWarning;
    }
}
