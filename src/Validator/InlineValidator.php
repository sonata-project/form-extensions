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

use Sonata\Form\Validator\Constraints\InlineConstraint;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class InlineValidator extends ConstraintValidator
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConstraintValidatorFactoryInterface
     */
    protected $constraintValidatorFactory;

    /**
     * @psalm-suppress ContainerDependency
     */
    public function __construct(
        ContainerInterface $container,
        ConstraintValidatorFactoryInterface $constraintValidatorFactory
    ) {
        $this->container = $container;
        $this->constraintValidatorFactory = $constraintValidatorFactory;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof InlineConstraint) {
            throw new UnexpectedTypeException($constraint, InlineConstraint::class);
        }

        if ($constraint->isClosure()) {
            $function = $constraint->getClosure();
        } else {
            if (\is_string($constraint->getService())) {
                $service = $this->container->get($constraint->getService());
            } else {
                $service = $constraint->getService();
            }

            $function = [$service, $constraint->getMethod()];
        }

        \call_user_func($function, $this->getErrorElement($value), $value);
    }

    /**
     * @param mixed $value
     */
    protected function getErrorElement($value): ErrorElement
    {
        return new ErrorElement(
            $value,
            $this->constraintValidatorFactory,
            $this->context,
            $this->context->getGroup()
        );
    }
}
