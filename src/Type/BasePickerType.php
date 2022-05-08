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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BasePickerType (to factorize DatePickerType and DateTimePickerType code.
 *
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 *
 * @author Hugo Briand <briand@ekino.com>
 */
abstract class BasePickerType extends AbstractType
{
    /**
     * @var array<string, array<string>|string>
     */
    private const DATEPICKER_ALLOWED_OPTIONS = [
        'altFormat' => 'string',
        'altInput' => 'bool',
        'altInputClass' => 'string',
        'allowInput' => 'bool',
        'allowValidPreload' => 'bool',
        'ariaDateFormat' => 'string',
        'conjunction' => 'string',
        'clickOpens' => 'bool',
        'dateFormat' => 'string',
        'defaultDate' => ['string', 'string[]', \DateTimeInterface::class, 'DateTimeInterface[]'],
        'defaultHour' => 'integer',
        'defaultMinute' => 'integer',
        'disable' => ['string[]', 'DateTimeInterface[]'], // array<{ from: string|Date, to: string|Date }>
        'disableMobile' => 'bool',
        'enable' => ['string[]', 'DateTimeInterface[]'], // array<{ from: string|Date, to: string|Date }>
        'enableTime' => 'bool',
        'enableSeconds' => 'bool',
        'hourIncrement' => 'integer',
        'inline' => 'bool',
        'maxDate' => ['string', \DateTimeInterface::class],
        'minDate' => ['string', \DateTimeInterface::class],
        'minuteIncrement' => 'integer',
        'mode' => 'string',
        'nextArrow' => 'string',
        'noCalendar' => 'bool',
        'position' => 'string',
        'prevArrow' => 'string',
        'shorthandCurrentMonth' => 'bool',
        'static' => 'bool',
        'showMonths' => 'integer',
        'time_24hr' => 'bool',
        'weekNumbers' => 'bool',
        'wrap' => 'bool',
        'monthSelectorType' => 'string',
    ];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('datepicker_options', function (OptionsResolver $datePickerResolver) {
            $dateTimeNormalizer = static function (OptionsResolver $options, string|array|\DateTimeInterface $value): string|array {
                if ($value instanceof \DateTimeInterface) {
                    return $value->format(\DateTimeInterface::ATOM);
                }

                if (\is_array($value)) {
                    foreach ($value as $key => $singleValue) {
                        if ($singleValue instanceof \DateTimeInterface) {
                            $value[$key] = $singleValue->format(\DateTimeInterface::ATOM);
                        }
                    }
                }

                return $value;
            };

            $datePickerResolver->setDefined(array_keys(self::DATEPICKER_ALLOWED_OPTIONS));
            $datePickerResolver->setNormalizer('enable', $dateTimeNormalizer);
            $datePickerResolver->setNormalizer('disable', $dateTimeNormalizer);
            $datePickerResolver->setNormalizer('maxDate', $dateTimeNormalizer);
            $datePickerResolver->setNormalizer('minDate', $dateTimeNormalizer);
            $datePickerResolver->setNormalizer('defaultDate', $dateTimeNormalizer);
            $datePickerResolver->setDefaults($this->getCommonDatepickerDefaults());

            foreach (self::DATEPICKER_ALLOWED_OPTIONS as $option => $allowedTypes) {
                $datePickerResolver->setAllowedTypes($option, $allowedTypes);
            }
        });

        $resolver->setDefaults($this->getCommonDefaults());

        $resolver->setAllowedTypes('datepicker_use_button', 'bool');
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['datepicker_use_button'] = $options['datepicker_use_button'] ?? false;
        $view->vars['datepicker_options'] = $options['datepicker_options'];
    }

    /**
     * Gets base default options for the form types
     * (except `datepicker_options` which should be handled with `getCommonDatepickerDefaults()`).
     *
     * @return array<string, mixed>
     */
    protected function getCommonDefaults(): array
    {
        return [
            'widget' => 'single_text',
            'datepicker_use_button' => true,
            'html5' => false,
        ];
    }

    /**
     * Gets base default options for the `datepicker_options` option.
     *
     * @return array<string, mixed>
     */
    protected function getCommonDatepickerDefaults(): array
    {
        return [
            'allowInput' => true,
            'wrap' => true,

            // 'altFormat' => 'F j, Y',
            // 'altInputClass' => '',
            // 'allowValidPreload' => false,
            // 'ariaDateFormat' => 'F j, Y',
            // 'conjunction' => null,
            // 'clickOpens' => true,
            // 'dateFormat' => 'Y-m-d',
            // 'defaultDate' => null,
            // 'defaultHour' => 12,
            // 'defaultMinute' => 0,
            // 'disable' => [],
            // 'disableMobile' => false,
            // 'enable' => [],
            // 'enableTime' => false,
            // 'enableSeconds' => false,
            // 'hourIncrement' => 1,
            // 'inline' => false,
            // 'maxDate' => null,
            // 'minDate' => null,
            // 'minuteIncrement' => 5,
            // 'mode' => 'single',
            // 'nextArrow' => '>',
            // 'noCalendar' => false,
            // 'position' => 'auto',
            // 'prevArrow' => '<',
            // 'shorthandCurrentMonth' => false,
            // 'static' => false,
            // 'showMonths' => 1,
            // 'time_24hr' => false,
            // 'weekNumbers' => false,
            // 'monthSelectorType' => 'dropdown',
        ];
    }
}
