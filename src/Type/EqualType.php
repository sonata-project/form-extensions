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
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

@trigger_error(
    'The '.__NAMESPACE__.'\EqualType class is deprecated since version 0.x and will be removed in 2.0.'
    .' Use Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType instead.',
    E_USER_DEPRECATED
);

/**
 * NEXT_MAJOR: remove this class.
 *
 * @deprecated since sonata-project/form-extensions 0.x, to be removed with 2.0
 *
 * @final since sonata-project/form-extensions 0.x
 */
class EqualType extends AbstractType
{
    public const TYPE_IS_EQUAL = 1;

    public const TYPE_IS_NOT_EQUAL = 2;

    /**
     * NEXT_MAJOR: remove this property.
     *
     * @var LegacyTranslatorInterface|TranslatorInterface|null
     *
     * @deprecated translator property is deprecated since sonata-project/form-extensions 0.x, to be removed in 1.0
     */
    protected $translator;

    /**
     * NEXT_MAJOR: remove this method.
     *
     * @deprecated translator dependency is deprecated since sonata-project/form-extensions 0.x, to be removed in 1.0
     */
    public function __construct($translator = null)
    {
        if (
            !$translator instanceof LegacyTranslatorInterface &&
            !$translator instanceof TranslatorInterface &&
            null !== $translator
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 should be an instance of %s or %s',
                LegacyTranslatorInterface::class,
                TranslatorInterface::class
            ));
        }

        // check if class is overloaded and notify about removing deprecated translator
        if (null !== $translator && __CLASS__ !== static::class) {
            @trigger_error(
                'The translator dependency in '.__CLASS__.' is deprecated since 0.x and will be removed in 1.0. '.
                'Please prepare your dependencies for this change.',
                E_USER_DEPRECATED
            );
        }

        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultOptions = [
            'choice_translation_domain' => 'SonataCoreBundle',
            'choices' => [
                'label_type_equals' => self::TYPE_IS_EQUAL,
                'label_type_not_equals' => self::TYPE_IS_NOT_EQUAL,
            ],
        ];

        $resolver->setDefaults($defaultOptions);
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
        return 'sonata_type_equal';
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
}
