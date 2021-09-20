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

use Sonata\Form\Date\MomentFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class BasePickerType (to factorize DatePickerType and DateTimePickerType code.
 *
 * @author Hugo Briand <briand@ekino.com>
 */
abstract class BasePickerType extends AbstractType implements LocaleAwareInterface
{
    /**
     * @var TranslatorInterface|null
     */
    protected $translator;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var MomentFormatConverter
     */
    private $formatConverter;

    /**
     * NEXT_MAJOR: Add "string" typehint to $requestStackOrDefaultLocale and change the name to defaultLocale.
     */
    public function __construct(MomentFormatConverter $formatConverter, TranslatorInterface $translator, $requestStackOrDefaultLocale)
    {
        $this->formatConverter = $formatConverter;
        $this->translator = $translator;

        // NEXT_MAJOR: Remove this block
        if (!\is_string($requestStackOrDefaultLocale) && !$requestStackOrDefaultLocale instanceof RequestStack) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 3 passed to "%s()" must be of type string or an instance of %s, %s given.',
                __METHOD__,
                \is_object($requestStackOrDefaultLocale) ? 'instance of '.\get_class($requestStackOrDefaultLocale) : \gettype($requestStackOrDefaultLocale),
                RequestStack::class
            ));
        }

        // NEXT_MAJOR: Remove this block
        if (!\is_string($requestStackOrDefaultLocale)) {
            @trigger_error(sprintf(
                'Not passing the default locale as argument 3 to "%s()" is deprecated'
                .' since sonata-project/form-extensions 1.6 and will be mandatory in 2.0.',
                __METHOD__
            ), \E_USER_DEPRECATED);

            $requestStackOrDefaultLocale = $this->getLocaleFromRequest($requestStackOrDefaultLocale);
        }

        $this->locale = $requestStackOrDefaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer('format', function (Options $options, $format) {
            if (isset($options['date_format']) && \is_string($options['date_format'])) {
                return $options['date_format'];
            }

            if (\is_int($format)) {
                $timeFormat = \IntlDateFormatter::NONE;
                if ($options['dp_pick_time']) {
                    $timeFormat = $options['dp_use_seconds'] ?
                        DateTimeType::DEFAULT_TIME_FORMAT :
                        \IntlDateFormatter::SHORT;
                }
                $intlDateFormatter = new \IntlDateFormatter(
                    $this->locale,
                    $format,
                    $timeFormat,
                    null,
                    \IntlDateFormatter::GREGORIAN
                );

                return $intlDateFormatter->getPattern();
            }

            return $format;
        });
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $format = $options['format'];

        // use seconds if it's allowed in format
        $options['dp_use_seconds'] = false !== strpos($format, 's');

        if ($options['dp_min_date'] instanceof \DateTimeInterface) {
            $options['dp_min_date'] = $this->formatObject($options['dp_min_date'], $format);
        }
        if ($options['dp_max_date'] instanceof \DateTimeInterface) {
            $options['dp_max_date'] = $this->formatObject($options['dp_max_date'], $format);
        }

        $view->vars['moment_format'] = $this->formatConverter->convert($format);

        $view->vars['type'] = 'text';

        $dpOptions = [];
        foreach ($options as $key => $value) {
            if (false !== strpos($key, 'dp_')) {
                // We remove 'dp_' and camelize the options names
                $dpKey = substr($key, 3);
                $dpKey = preg_replace_callback('/_([a-z])/', static function (array $c): string {
                    return strtoupper($c[1]);
                }, $dpKey);

                $dpOptions[$dpKey] = $value;
            }
        }

        $view->vars['datepicker_use_button'] = isset($options['datepicker_use_button']) && true === $options['datepicker_use_button'];
        $view->vars['dp_options'] = $dpOptions;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    /**
     * Gets base default options for the date pickers.
     */
    protected function getCommonDefaults(): array
    {
        return [
            'widget' => 'single_text',
            'datepicker_use_button' => true,
            'dp_pick_time' => true,
            'dp_pick_date' => true,
            'dp_use_current' => true,
            'dp_min_date' => '1/1/1900',
            'dp_max_date' => null,
            'dp_show_today' => true,
            'dp_language' => $this->locale,
            'dp_default_date' => '',
            'dp_disabled_dates' => [],
            'dp_enabled_dates' => [],
            'dp_icons' => [
                'time' => 'fa fa-clock-o',
                'date' => 'fa fa-calendar',
                'up' => 'fa fa-chevron-up',
                'down' => 'fa fa-chevron-down',
            ],
            'dp_use_strict' => false,
            'dp_side_by_side' => false,
            'dp_days_of_week_disabled' => [],
            'dp_collapse' => true,
            'dp_calendar_weeks' => false,
            'dp_view_mode' => 'days',
            'dp_min_view_mode' => 'days',
        ];
    }

    private function getLocaleFromRequest(RequestStack $requestStack): string
    {
        $request = $requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \LogicException('A Request must be available.');
        }

        return $request->getLocale();
    }

    private function formatObject(\DateTimeInterface $dateTime, $format): string
    {
        $formatter = new \IntlDateFormatter($this->locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE);
        $formatter->setPattern($format);

        return $formatter->format($dateTime);
    }
}
