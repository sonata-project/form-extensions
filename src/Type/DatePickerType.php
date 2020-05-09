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

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Hugo Briand <briand@ekino.com>
 *
 * @final since sonata-project/form-extensions 0.x
 */
class DatePickerType extends BasePickerType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array_merge($this->getCommonDefaults(), [
            'dp_pick_time' => false,
            'format' => DateType::DEFAULT_FORMAT,
        ]));

        parent::configureOptions($resolver);
    }

    /**
     * @return string,
     */
    public function getParent()
    {
        return DateType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_date_picker';
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
