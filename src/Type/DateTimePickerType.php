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

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 *
 * @author Hugo Briand <briand@ekino.com>
 */
final class DateTimePickerType extends BasePickerType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array_merge($this->getCommonDefaults(), [
            'dp_use_minutes' => true,
            'dp_use_seconds' => true,
            'dp_minute_stepping' => 1,
            'format' => DateTimeType::DEFAULT_DATE_FORMAT,
            'date_format' => null,
            'html5' => false,
        ]));

        $resolver->setAllowedTypes('dp_use_minutes', 'bool');
        $resolver->setAllowedTypes('dp_use_seconds', 'bool');
        $resolver->setAllowedTypes('dp_minute_stepping', 'int');
        $resolver->setAllowedTypes('format', ['null', 'int', 'string']);
        $resolver->setAllowedTypes('date_format', ['null', 'string']);
        $resolver->setAllowedTypes('html5', 'bool');

        parent::configureOptions($resolver);
    }

    public function getParent(): string
    {
        return DateTimeType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sonata_type_datetime_picker';
    }
}
