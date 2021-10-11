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

namespace Sonata\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseStatusType extends AbstractType
{
    /**
     * @var string
     * @phpstan-var class-string
     */
    protected $class;

    /**
     * @var string
     */
    protected $getter;

    /**
     * @var string
     */
    protected $name;

    /**
     * @phpstan-param class-string $class
     */
    public function __construct(string $class, string $getter, string $name)
    {
        $this->class = $class;
        $this->getter = $getter;
        $this->name = $name;
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return $this->name;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $callable = [$this->class, $this->getter];
        if (!\is_callable($callable)) {
            throw new \RuntimeException(sprintf(
                'The class "%s" has no method "%s()".',
                $this->class,
                $this->getter
            ));
        }

        $resolver->setDefaults([
            'choices' => \call_user_func($callable),
        ]);
    }
}
