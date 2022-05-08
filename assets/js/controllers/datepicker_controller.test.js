/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Application, Controller } from '@hotwired/stimulus';
import { getByTestId, waitFor } from '@testing-library/dom';
import userEvent from '@testing-library/user-event';
import Flatpickr from 'flatpickr';
import DatepickerController from './datepicker_controller';

class CheckController extends Controller {
  initialize() {
    this.element.addEventListener('datepicker:pre-initialize', () => {
      this.element.classList.add('pre-initialized');
    });
  }

  connect() {
    this.element.addEventListener('datepicker:pre-connect', () => {
      this.element.classList.add('pre-connected');
    });

    this.element.addEventListener('datepicker:connect', () => {
      this.element.classList.add('connected');
    });
  }
}

const startStimulus = () => {
  const application = Application.start();

  application.register('check', CheckController);
  application.register('datepicker', DatepickerController);
};

const flatpickrCalendar = () => document.querySelector('.flatpickr-calendar');

describe('DatepickerController', () => {
  it('connect without options', async () => {
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
      />
    `;

    const mainElement = getByTestId(document, 'main-element');

    expect(mainElement).not.toHaveClass('pre-initialized');
    expect(mainElement).not.toHaveClass('pre-connected');
    expect(mainElement).not.toHaveClass('connected');

    startStimulus();

    await waitFor(() => {
      expect(mainElement).toHaveClass('pre-initialized');
      expect(mainElement).toHaveClass('pre-connected');
      expect(mainElement).toHaveClass('connected');
    });
  });

  it('initializes flatpickr', async () => {
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
      />
    `;

    const mainElement = getByTestId(document, 'main-element');

    startStimulus();

    await waitFor(() => {
      expect(mainElement).toHaveClass('connected');
    });

    expect(mainElement).toHaveClass('flatpickr-input');
  });

  it('can be opened', async () => {
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
      />
    `;

    const mainElement = getByTestId(document, 'main-element');

    startStimulus();

    await waitFor(() => {
      expect(mainElement).toHaveClass('connected');
    });

    expect(flatpickrCalendar()).not.toHaveClass('open');

    await userEvent.click(mainElement);

    expect(flatpickrCalendar()).toHaveClass('open');
  });

  it('can receive options', async () => {
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
        data-datepicker-options-value='${JSON.stringify({ enableTime: true })}'
      />
    `;

    startStimulus();

    await waitFor(() => {
      expect(getByTestId(document, 'main-element')).toHaveClass('connected');
    });

    expect(flatpickrCalendar()).toHaveClass('hasTime');
  });

  it('can be localized', async () => {
    document.documentElement.lang = 'fr';
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
      />
    `;

    startStimulus();

    await waitFor(() => {
      expect(getByTestId(document, 'main-element')).toHaveClass('connected');
    });

    expect(Flatpickr.l10ns.default).toEqual(expect.objectContaining(Flatpickr.l10ns.fr));
  });

  it('can select a date', async () => {
    document.body.innerHTML = `
      <input
        type="text"
        data-testid="main-element"
        data-controller="check datepicker"
      />
    `;

    const mainElement = getByTestId(document, 'main-element');

    startStimulus();

    await waitFor(() => {
      expect(mainElement).toHaveClass('connected');
    });

    await userEvent.click(mainElement);

    expect(mainElement.value).toBe('');

    await userEvent.click(document.querySelector('.flatpickr-day'));

    expect(flatpickrCalendar()).not.toHaveClass('open');
    expect(mainElement.value).not.toBe('');
  });
});
