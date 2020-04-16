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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * DateTimeRangePickerType.
 *
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
class DateTimeRangePickerType extends DateTimeRangeType
{
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
            'field_options' => [],
            'field_options_start' => [],
            'field_options_end' => [
                'dp_use_current' => false,
            ],
            'field_type' => DateTimePickerType::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_datetime_range_picker';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
