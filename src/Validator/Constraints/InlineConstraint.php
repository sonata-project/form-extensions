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

/**
 * Constraint which allows inline-validation inside services.
 *
 * @Annotation
 * @Target({"CLASS"})
 *
 * @final since sonata-project/form-extensions 0.x
 */
class InlineConstraint extends Constraint
{
    /**
     * @var mixed
     */
    protected $service;

    /**
     * @var mixed
     */
    protected $method;

    /**
     * @var mixed
     */
    protected $serializingWarning;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if ((!\is_string($this->service) || !\is_string($this->method)) && true !== $this->serializingWarning) {
            throw new \RuntimeException('You are using a closure with the `InlineConstraint`, this constraint'.
                ' cannot be serialized. You need to re-attach the `InlineConstraint` on each request.'.
                ' Once done, you can set the `serializingWarning` option to `true` to avoid this message.');
        }
    }

    public function __sleep(): array
    {
        if (!\is_string($this->service) || !\is_string($this->method)) {
            return [];
        }

        // Initialize "groups" option if it is not set
        $this->groups;

        return array_keys(get_object_vars($this));
    }

    public function __wakeup()
    {
        if (\is_string($this->service) && \is_string($this->method)) {
            return;
        }

        $this->method = static function () {
        };

        $this->serializingWarning = true;
    }

    public function validatedBy()
    {
        return 'sonata.form.validator.inline';
    }

    /**
     * @return bool
     */
    public function isClosure()
    {
        return $this->method instanceof \Closure;
    }

    /**
     * @return mixed
     */
    public function getClosure()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return [
            'service',
            'method',
        ];
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return mixed
     */
    public function getSerializingWarning()
    {
        return $this->serializingWarning;
    }
}
