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

namespace Sonata\Form\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

final class CanonicalizeRuntime implements RuntimeExtensionInterface
{
    // @todo: there are more locales which are not supported by "Moment.js" NPM library and they need to be translated/normalized/canonicalized here
    private const MOMENT_UNSUPPORTED_LOCALES = [
        'de' => ['de', 'de-at'],
        'es' => ['es', 'es-do'],
        'nl' => ['nl', 'nl-be'],
        'fr' => ['fr', 'fr-ca', 'fr-ch'],
    ];

    /**
     * @internal This class should only be used through Twig
     */
    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * Returns a canonicalized locale for "Moment.js" NPM library,
     * or `null` if the locale's language is "en", which doesn't require localization.
     */
    public function getCanonicalizedLocaleForMoment(): ?string
    {
        $locale = $this->getLocale();

        // "en" language doesn't require localization.
        if (('en' === $lang = substr($locale, 0, 2)) && !\in_array($locale, ['en-au', 'en-ca', 'en-gb', 'en-ie', 'en-nz'], true)) {
            return null;
        }

        foreach (self::MOMENT_UNSUPPORTED_LOCALES as $language => $locales) {
            if ($language === $lang && !\in_array($locale, $locales, true)) {
                $locale = $language;
            }
        }

        // Handle locales that has equal langage part and country part.
        if (str_contains($locale, '-')) {
            $localeParts = explode('-', strtolower($locale));
            if ($localeParts[0] === $localeParts[1]) {
                $locale = $localeParts[0];
            }
        }

        return $locale;
    }

    private function getLocale(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new \LogicException('The request stack is empty.');
        }

        return str_replace('_', '-', $request->getLocale());
    }
}
