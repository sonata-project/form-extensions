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
     * @deprecated Since 0.x, to be remove in 1.0. Use configureOptions instead.
     *
     * @var bool
     */
    protected $flip;

    /**
     * @param string $class
     * @param string $getter
     * @param string $name
     * @param bool   $flip   reverse key/value to match sf2.8 and sf3.0 change
     */
    public function __construct($class, $getter, $name, $flip = null)
    {
        if (null !== $flip) {
            @trigger_error(
                'Passing "flip" in argument 4 for '.__METHOD__.'() is deprecated since sonata-project/form-extensions 0.x, to be removed with 1.0.',
                E_USER_DEPRECATED
            );
        }

        $this->class = $class;
        $this->getter = $getter;
        $this->name = $name;
        $this->flip = $flip ?? true;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return $this->name;
    }

    /**
     * @deprecated since 0.x to be removed in 1.x. Use getBlockPrefix() instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = \call_user_func([$this->class, $this->getter]);

        // NEXT_MAJOR: remove this property
        if ($this->flip) {
            $count = \count($choices);

            $choices = array_flip($choices);

            if (\count($choices) !== $count) {
                throw new \LengthException('Unable to safely flip value as final count is different.');
            }
        }

        $resolver->setDefaults([
            'choices' => $choices,
        ]);
    }
}
