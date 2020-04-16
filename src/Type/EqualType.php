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
    'The '.__NAMESPACE__.'\EqualType class is deprecated since version 1.2 and will be removed in 2.0.'
    .' Use Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType instead.',
    E_USER_DEPRECATED
);

/**
 * NEXT_MAJOR: remove this class.
 *
 * @deprecated since sonata-project/form-extensions 1.2, to be removed with 2.0
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
     *
     * @param LegacyTranslatorInterface|TranslatorInterface|null $translator
     */
    public function __construct($translator = null)
    {
        if (!$translator instanceof LegacyTranslatorInterface && !$translator instanceof TranslatorInterface && null !== $translator) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 should be an instance of %s or %s or %s',
                LegacyTranslatorInterface::class,
                TranslatorInterface::class,
                'null'
            ));
        }

        if (null !== $translator && __CLASS__ !== static::class && DateRangePickerType::class !== static::class) {
            @trigger_error(
                sprintf(
                    'The translator dependency in %s is deprecated since 0.x and will be removed in 1.0. '.
                    'Please do not call %s with translator argument in %s.',
                    __CLASS__,
                    __METHOD__,
                    static::class
                ),
                E_USER_DEPRECATED
            );
        }

        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_translation_domain' => 'SonataFormBundle',
            'choices' => [
                'label_type_equals' => self::TYPE_IS_EQUAL,
                'label_type_not_equals' => self::TYPE_IS_NOT_EQUAL,
            ],
        ]);
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
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
