/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import Flatpickr from 'flatpickr';
import l10n from 'flatpickr/dist/l10n';

export default class extends Controller {
  static values = {
    options: Object,
  };

  initialize() {
    const { lang: locale } = document.documentElement;

    this.dispatchEvent('datepicker:pre-initialize', { locale });

    if (locale in l10n) {
      Flatpickr.localize(l10n[locale]);
    }
  }

  connect() {
    const options = this.optionsValue;

    this.dispatchEvent('datepicker:pre-connect', { options });

    const datePicker = new Flatpickr(this.element, options);

    this.dispatchEvent('datepicker:connect', { datePicker });
  }

  dispatchEvent(name, payload) {
    this.element.dispatchEvent(new CustomEvent(name, { detail: payload }));
  }
}
