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

use Sonata\Form\DataTransformer\BooleanTypeToBooleanTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @final since sonata-project/form-extensions 0.x
 */
class BooleanType extends AbstractType
{
    public const TYPE_YES = 1;

    public const TYPE_NO = 2;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['transform']) {
            $builder->addModelTransformer(new BooleanTypeToBooleanTransformer());
        }

        if ('SonataCoreBundle' !== $options['catalogue']) {
            @trigger_error(
                'Option "catalogue" is deprecated since sonata-project/form-extensions 0.x and will be removed in 1.0.'
                .' Use option "translation_domain" instead.',
                E_USER_DEPRECATED
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultOptions = [
            'transform' => false,
            /*
             * NEXT_MAJOR: remove this block.
             * @deprecated since sonata-project/form-extensions 0.x, to be removed in 1.0.
             */
            'catalogue' => 'SonataCoreBundle',
            'choice_translation_domain' => 'SonataCoreBundle',
            'choices' => [
                'label_type_yes' => self::TYPE_YES,
                'label_type_no' => self::TYPE_NO,
            ],
            // Use directly translation_domain
            'translation_domain' => static function (Options $options) {
                if ($options['catalogue']) {
                    return $options['catalogue'];
                }

                return $options['translation_domain'];
            },
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
     * @deprecated since 0.x to be removed in 1.x. Use getBlockPrefix() instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_boolean';
    }
}
