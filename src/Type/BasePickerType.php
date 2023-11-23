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

use Sonata\Form\Date\JavaScriptFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\LocaleAwareInterface;

/**
 * Class BasePickerType (to factorize DatePickerType and DateTimePickerType code.
 *
 * @psalm-suppress MissingTemplateParam https://github.com/phpstan/phpstan-symfony/issues/320
 *
 * @author Hugo Briand <briand@ekino.com>
 */
abstract class BasePickerType extends AbstractType implements LocaleAwareInterface
{
    /**
     * @var array<string, array<string>|string>
     */
    private const DATEPICKER_ALLOWED_OPTIONS = [
        'allowInputToggle' => 'bool',
        'dateRange' => 'bool',
        'debug' => 'bool',
        'defaultDate' => ['string', \DateTimeInterface::class],
        'keepInvalid' => 'bool',
        'multipleDates' => 'bool',
        'multipleDatesSeparator' => 'string',
        'promptTimeOnDateChange' => 'bool',
        'promptTimeOnDateChangeTransitionDelay' => 'integer',
        'stepping' => 'integer',
        'useCurrent' => 'bool',
        'viewDate' => ['string', \DateTimeInterface::class],
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const RESTRICTIONS_OPTIONS = [
        'minDate' => ['string', \DateTimeInterface::class],
        'maxDate' => ['string', \DateTimeInterface::class],
        'disabledDates' => ['string[]', 'DateTimeInterface[]'],
        'enabledDates' => ['string[]', 'DateTimeInterface[]'],
        'daysOfWeekDisabled' => 'integer[]',
        'disabledHours' => 'integer[]',
        'enabledHours' => 'integer[]',
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const LOCALIZATION_OPTIONS = [
        'locale' => 'string',
        'hourCycle' => 'string',
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const DISPLAY_OPTIONS = [
        'sideBySide' => 'bool',
        'calendarWeeks' => 'bool',
        'viewMode' => 'string',
        'toolbarPlacement' => 'string',
        'keepOpen' => 'bool',
        'inline' => 'bool',
        'theme' => 'string',
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const DISPLAY_ICONS_OPTIONS = [
        'time' => 'string',
        'date' => 'string',
        'up' => 'string',
        'down' => 'string',
        'previous' => 'string',
        'next' => 'string',
        'today' => 'string',
        'clear' => 'string',
        'close' => 'string',
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const DISPLAY_BUTTONS_OPTIONS = [
        'today' => 'bool',
        'clear' => 'bool',
        'close' => 'bool',
    ];

    /**
     * @var array<string, array<string>|string>
     */
    private const DISPLAY_COMPONENTS_OPTIONS = [
        'calendar' => 'bool',
        'date' => 'bool',
        'month' => 'bool',
        'year' => 'bool',
        'decades' => 'bool',
        'clock' => 'bool',
        'hours' => 'bool',
        'minutes' => 'bool',
        'seconds' => 'bool',
    ];

    public function __construct(
        private JavaScriptFormatConverter $formatConverter,
        private string $locale
    ) {
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults($this->getCommonDefaults());

        $resolver->setDefault('datepicker_options', function (OptionsResolver $datePickerResolver) {
            $datePickerResolver->setDefined(array_keys(self::DATEPICKER_ALLOWED_OPTIONS));

            foreach (self::DATEPICKER_ALLOWED_OPTIONS as $option => $allowedTypes) {
                $datePickerResolver->setAllowedTypes($option, $allowedTypes);
            }

            $datePickerResolver->setNormalizer('defaultDate', $this->dateTimeNormalizer());
            $datePickerResolver->setNormalizer('viewDate', $this->dateTimeNormalizer());

            $defaults = $this->getCommonDatepickerDefaults();

            $datePickerResolver->setDefaults($defaults);
            $datePickerResolver->setDefault('localization', $this->defineLocalizationOptions($defaults['localization'] ?? []));
            $datePickerResolver->setDefault('restrictions', $this->defineRestrictionsOptions($defaults['restrictions'] ?? []));
            $datePickerResolver->setDefault('display', $this->defineDisplayOptions($defaults['display'] ?? []));
        });

        $resolver->setNormalizer(
            'format',
            function (Options $options, int|string $format): string {
                if (\is_int($format)) {
                    $timeFormat = \IntlDateFormatter::NONE;

                    if (true === ($options['datepicker_options']['display']['components']['clock'] ?? true)) {
                        $timeFormat = true === ($options['datepicker_options']['display']['components']['seconds'] ?? false) ?
                            DateTimeType::DEFAULT_TIME_FORMAT :
                            \IntlDateFormatter::SHORT;
                    }

                    return (new \IntlDateFormatter(
                        $this->locale,
                        $format,
                        $timeFormat,
                        null,
                        \IntlDateFormatter::GREGORIAN
                    ))->getPattern();
                }

                return $format;
            }
        );

        $resolver->setAllowedTypes('datepicker_use_button', 'bool');
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $datePickerOptions = $options['datepicker_options'] ?? [];

        if (isset($datePickerOptions['display']['icons'])
            && [] === $datePickerOptions['display']['icons']) {
            unset($datePickerOptions['display']['icons']);
        }

        if (isset($datePickerOptions['display']['buttons'])
            && [] === $datePickerOptions['display']['buttons']) {
            unset($datePickerOptions['display']['buttons']);
        }

        if (isset($datePickerOptions['display']['components'])
            && [] === $datePickerOptions['display']['components']) {
            unset($datePickerOptions['display']['components']);
        }

        if (isset($datePickerOptions['display'])
            && [] === $datePickerOptions['display']) {
            unset($datePickerOptions['display']);
        }

        if (isset($datePickerOptions['restrictions'])
            && [] === $datePickerOptions['restrictions']) {
            unset($datePickerOptions['restrictions']);
        }

        if (!isset($datePickerOptions['localization'])) {
            $datePickerOptions['localization'] = [];
        }

        $datePickerOptions['localization']['format'] = $this->formatConverter->convert($options['format'] ?? '');

        $view->vars['datepicker_options'] = $datePickerOptions;
        $view->vars['datepicker_use_button'] = $options['datepicker_use_button'] ?? false;
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
            'display' => [
                'theme' => 'light',
            ],
            'localization' => [
                'locale' => str_replace('_', '-', $this->locale),
            ],
        ];
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineLocalizationOptions(array $defaults): callable
    {
        return static function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::LOCALIZATION_OPTIONS));

            foreach (self::LOCALIZATION_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setDefaults($defaults);
        };
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineRestrictionsOptions(array $defaults): callable
    {
        return function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::RESTRICTIONS_OPTIONS));

            foreach (self::RESTRICTIONS_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setAllowedValues(
                'daysOfWeekDisabled',
                static fn (array $value) => array_filter(
                    $value,
                    static fn ($day) => \is_int($day) && $day >= 0 && $day <= 6
                ) === $value
            );

            $resolver->setAllowedValues(
                'enabledHours',
                static fn (array $value) => array_filter(
                    $value,
                    static fn ($hour) => \is_int($hour) && $hour >= 0 && $hour <= 23
                ) === $value
            );

            $resolver->setAllowedValues(
                'disabledHours',
                static fn (array $value) => array_filter(
                    $value,
                    static fn ($hour) => \is_int($hour) && $hour >= 0 && $hour <= 23
                ) === $value
            );

            $resolver->setNormalizer('minDate', $this->dateTimeNormalizer());
            $resolver->setNormalizer('maxDate', $this->dateTimeNormalizer());
            $resolver->setNormalizer('disabledDates', $this->dateTimeNormalizer());
            $resolver->setNormalizer('enabledDates', $this->dateTimeNormalizer());

            $resolver->setDefaults($defaults);
        };
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineDisplayOptions(array $defaults): callable
    {
        return function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::DISPLAY_OPTIONS));

            foreach (self::DISPLAY_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setAllowedValues('viewMode', ['clock', 'calendar', 'months', 'years', 'decades']);
            $resolver->setAllowedValues('toolbarPlacement', ['top', 'bottom']);
            $resolver->setAllowedValues('theme', ['light', 'dark', 'auto']);

            $resolver->setDefaults($defaults);
            $resolver->setDefault('icons', $this->defineDisplayIconsOptions($defaults['icons'] ?? []));
            $resolver->setDefault('buttons', $this->defineDisplayButtonsOptions($defaults['buttons'] ?? []));
            $resolver->setDefault('components', $this->defineDisplayComponentsOptions($defaults['components'] ?? []));
        };
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineDisplayIconsOptions(array $defaults): callable
    {
        return static function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::DISPLAY_ICONS_OPTIONS));

            foreach (self::DISPLAY_ICONS_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setDefaults($defaults);
        };
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineDisplayButtonsOptions(array $defaults): callable
    {
        return static function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::DISPLAY_BUTTONS_OPTIONS));

            foreach (self::DISPLAY_BUTTONS_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setDefaults($defaults);
        };
    }

    /**
     * @param array<string, mixed> $defaults
     */
    private function defineDisplayComponentsOptions(array $defaults): callable
    {
        return static function (OptionsResolver $resolver) use ($defaults): void {
            $resolver->setDefined(array_keys(self::DISPLAY_COMPONENTS_OPTIONS));

            foreach (self::DISPLAY_COMPONENTS_OPTIONS as $option => $allowedTypes) {
                $resolver->setAllowedTypes($option, $allowedTypes);
            }

            $resolver->setDefaults($defaults);
        };
    }

    private function dateTimeNormalizer(): \Closure
    {
        return static function (OptionsResolver $options, string|array|\DateTimeInterface $value): string|array {
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
    }
}
