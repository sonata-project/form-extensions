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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['field_options_start'] = array_merge(
            [
                'label' => 'date_range_start',
                'translation_domain' => 'SonataCoreBundle',
            ],
            $options['field_options_start']
        );

        $options['field_options_end'] = array_merge(
            [
                'label' => 'date_range_end',
                'translation_domain' => 'SonataCoreBundle',
            ],
            $options['field_options_end']
        );

        $builder->add(
            'start',
            $options['field_type'],
            array_merge(['required' => false], $options['field_options'], $options['field_options_start'])
        );
        $builder->add(
            'end',
            $options['field_type'],
            array_merge(['required' => false], $options['field_options'], $options['field_options_end'])
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_type_datetime_range';
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
        $resolver->setDefaults([
            'field_options' => [],
            'field_options_start' => [],
            'field_options_end' => [],
            'field_type' => DateTimeType::class,
        ]);
    }
}
