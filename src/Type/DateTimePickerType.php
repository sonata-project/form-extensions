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

/**
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 *
 * @author Hugo Briand <briand@ekino.com>
 */
final class DateTimePickerType extends BasePickerType
{
    public function getParent(): string
    {
        return DateTimeType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sonata_type_datetime_picker';
    }

    protected function getCommonDefaults(): array
    {
        return array_merge(parent::getCommonDefaults(), [
            'format' => DateTimeType::DEFAULT_DATE_FORMAT,
        ]);
    }

    protected function getCommonDatepickerDefaults(): array
    {
        return array_merge_recursive(parent::getCommonDatepickerDefaults(), [
            'display' => [
                'components' => [
                    'seconds' => true,
                ],
            ],
        ]);
    }
}
