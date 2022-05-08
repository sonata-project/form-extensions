/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import { TempusDominus, Namespace, loadLocale } from '@eonasdan/tempus-dominus';
import { faFiveIcons } from '@eonasdan/tempus-dominus/dist/plugins/fa-five';

export default class extends Controller {
  static outlets = ['datepicker'];

  static values = {
    options: Object,
  };

  #allowedLocales = [
    'ar',
    'ar-SA',
    'de',
    'es',
    'fi',
    'fr',
    'it',
    'nl',
    'pl',
    'ro',
    'ru',
    'sl',
    'tr',
  ];

  datePicker = null;

  connect() {
    const options = this.processOptions();
    const locale = this.processLocale(options);

    this.dispatchEvent('pre-connect', { options, locale });

    this.datePicker = new TempusDominus(this.element, options);

    if (locale !== null) {
      import(`@eonasdan/tempus-dominus/dist/locales/${locale}`).then((data) => {
        loadLocale(data);

        this.datePicker.locale(data.name);
        this.datePicker.updateOptions(options);

        this.dispatchEvent('post-connect-changed-locale');
      });
    }

    this.dispatchEvent('connect', { datePicker: this.datePicker });
  }

  datepickerOutletConnected(outlet, element) {
    this.element.addEventListener(Namespace.events.change, (event) => {
      outlet.datePicker.updateOptions({
        restrictions: {
          minDate: event.detail.date,
        },
      });
    });

    element.addEventListener(Namespace.events.change, (event) => {
      this.datePicker.updateOptions({
        restrictions: {
          maxDate: event.detail.date,
        },
      });
    });
  }

  processOptions() {
    const options = this.optionsValue;
    const { restrictions = {}, display = {} } = options;

    if (options?.defaultDate) {
      options.defaultDate = new Date(options.defaultDate);
    }

    if (options?.viewDate) {
      options.viewDate = new Date(options.viewDate);
    }

    if (restrictions?.minDate) {
      restrictions.minDate = new Date(restrictions.minDate);
    }

    if (restrictions?.maxDate) {
      restrictions.maxDate = new Date(restrictions.maxDate);
    }

    if (restrictions?.disabledDates) {
      restrictions.disabledDates = restrictions.disabledDates.map((date) => new Date(date));
    }

    if (restrictions?.enabledDates) {
      restrictions.enabledDates = restrictions.enabledDates.map((date) => new Date(date));
    }

    if (!display?.icons) {
      display.icons = faFiveIcons;
    }

    return options;
  }

  processLocale(options) {
    const { localization: { locale } = {} } = options;

    if (!locale) {
      return null;
    }

    if (this.#allowedLocales.includes(locale)) {
      return locale;
    }

    if (!locale.includes('-')) {
      return null;
    }

    const localeCode = locale.split('-')[0];

    if (this.#allowedLocales.includes(localeCode)) {
      return localeCode;
    }

    return null;
  }

  dispatchEvent(name, payload) {
    this.dispatch(name, { detail: payload, prefix: 'datepicker' });
  }
}
