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

/**
 * @fileoverview
 * Stimulus controller to manage the Tempus Dominus datetime picker widget,
 * providing date/time selection with localization and configuration support.
 *
 * This controller supports dynamic locale loading, option processing,
 * and synchronization between linked datepicker outlets.
 *(c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author AGBOKOUDJO Franck <internationaleswebservices@gmail.com>
 * @license MIT
 * @see https://github.com/Eonasdan/tempus-dominus
 * @see https://stimulus.hotwired.dev/
 */
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

  /**
 * Called when the controller is connected to the DOM.
 * Initializes the Tempus Dominus date/time picker with processed options and locale.
 * Dispatches lifecycle events: 'pre-connect', 'post-connect-changed-locale', and 'connect'.
 * @returns {void}
 */
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

  /**
 * Called when a datepicker outlet is connected.
 * Sets up event listeners to update minDate and maxDate restrictions between linked pickers.
 * @param {HTMLElement} outlet - The outlet controller instance.
 * @param {HTMLElement} element - The DOM element of the outlet.
 * @returns {void}
 */
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

  /**
 * Processes the datetime picker options by converting string dates into Date objects,
 * and sets default icons if not provided.
 * @returns {Object} The processed options object ready to be passed to Tempus Dominus.
 */
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

  /**
 * Validates and extracts a supported locale from the given options.
 * Returns a locale string if supported, or null if none is valid.
 * @param {Object} options - Options that may contain localization settings.
 * @returns {string|null} The locale string or null if unsupported.
 */
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

  /**
 * Dispatches a custom event prefixed with 'datepicker' from this controller element.
 * @param {string} name - The event name (without prefix).
 * @param {Object} payload - The event detail payload.
 * @returns {void}
 */
  dispatchEvent(name, payload) {
    this.dispatch(name, { detail: payload, prefix: 'datepicker' });
  }
}
