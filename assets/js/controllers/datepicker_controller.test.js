/*!
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Application } from '@hotwired/stimulus';
import { getByTestId, waitFor } from '@testing-library/dom';
import userEvent from '@testing-library/user-event';
import { afterEach, beforeAll, describe, expect, it } from 'vitest';
import DatepickerController from './datepicker_controller';

const startDatepickerTest = async (html) => {
  let datePicker = null;
  let datePicker2 = null;

  document.body.addEventListener('datepicker:pre-connect', () => {
    document.body.classList.add('pre-connected');
  });

  document.body.addEventListener('datepicker:connect', (event) => {
    if (!datePicker) datePicker = event.detail.datePicker;
    else datePicker2 = event.detail.datePicker;

    document.body.classList.add('connected');
  });

  document.body.addEventListener('datepicker:post-connect-changed-locale', () => {
    document.body.classList.add('post-connect-changed-locale');
  });

  document.body.innerHTML = html;

  await waitFor(() => {
    expect(document.body).toHaveClass('pre-connected');
    expect(document.body).toHaveClass('connected');
  });

  if (!datePicker) {
    throw new Error('Missing DatePicker instance');
  }

  return { datePicker, datePicker2 };
};

const innerDatepickerTest = `
  <input
    type="text"
    data-td-target="#datepicker"
    data-testid="input"
  />
  <span
    data-td-target="#datepicker"
    data-td-toggle="datetimepicker"
    data-testid="icon"
  ><i class="fas fa-calendar"></i></span>
`;

const tempusDominusCalendar = () => document.querySelector('.tempus-dominus-widget.show');

describe('DatepickerController', () => {
  beforeAll(() => {
    const application = Application.start();

    application.register('datepicker', DatepickerController);
  });

  afterEach(() => {
    document.body.innerHTML = '';
  });

  it('connects without options', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    expect(datePicker).toBeDefined();
  });

  it('can be opened', async () => {
    await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    const icon = getByTestId(document, 'icon');

    expect(tempusDominusCalendar()).toBeNull();

    await userEvent.click(icon);

    expect(tempusDominusCalendar()).toHaveClass('show');
  });

  it('can receive options', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          display: { components: { calendar: false } },
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    const icon = getByTestId(document, 'icon');

    await userEvent.click(icon);

    expect(tempusDominusCalendar().querySelector('.date-container')).toBeNull();
    expect(datePicker.optionsStore.options.display.components.calendar).toBe(false);
  });

  it('can be localized', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          localization: { locale: 'fr' },
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    expect(datePicker.optionsStore.options.localization.locale).toBe('fr');

    const icon = getByTestId(document, 'icon');

    await userEvent.click(icon);

    await waitFor(() => {
      expect(document.body).toHaveClass('post-connect-changed-locale');
    });

    expect(tempusDominusCalendar().querySelector('.picker-switch')).toHaveProperty(
      'title',
      'Sélectionner le mois'
    );
  });

  it('can be localized for locales with dash', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          localization: { locale: 'fr-FR' },
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    expect(datePicker.optionsStore.options.localization.locale).toBe('fr-FR');

    const icon = getByTestId(document, 'icon');

    await userEvent.click(icon);

    await waitFor(() => {
      expect(document.body).toHaveClass('post-connect-changed-locale');
    });

    expect(tempusDominusCalendar().querySelector('.picker-switch')).toHaveProperty(
      'title',
      'Sélectionner le mois'
    );
  });

  it('it does not localize for non supported locales', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          localization: { locale: 'ca' },
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    expect(datePicker.optionsStore.options.localization.locale).toBe('ca');

    const icon = getByTestId(document, 'icon');

    await userEvent.click(icon);

    await waitFor(() => {
      expect(document.body).toHaveClass('post-connect-changed-locale');
    });

    expect(tempusDominusCalendar().querySelector('.picker-switch')).toHaveProperty(
      'title',
      'Select Month'
    );
  });

  it('it does not localize for non supported locales with dash', async () => {
    const { datePicker } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          localization: { locale: 'ca-ES' },
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    expect(datePicker.optionsStore.options.localization.locale).toBe('ca-ES');

    const icon = getByTestId(document, 'icon');

    await userEvent.click(icon);

    await waitFor(() => {
      expect(document.body).toHaveClass('post-connect-changed-locale');
    });

    expect(tempusDominusCalendar().querySelector('.picker-switch')).toHaveProperty(
      'title',
      'Select Month'
    );
  });

  it('can select a date', async () => {
    await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          useCurrent: false,
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    const icon = getByTestId(document, 'icon');
    const input = getByTestId(document, 'input');

    await userEvent.click(icon);

    const calendar = tempusDominusCalendar();

    expect(calendar).toHaveClass('show');
    expect(input.value).toBe('');

    await userEvent.click(calendar.querySelector('.day'));

    expect(input.value).not.toBe('');
    expect(calendar).toHaveClass('show');

    await userEvent.click(document.body);

    expect(calendar).not.toHaveClass('show');
  });

  it('can be used without icon', async () => {
    await startDatepickerTest(`
      <input
        id="datepicker"
        type="text"
        data-testid="input"
        data-controller="datepicker"
      />
    `);

    const input = getByTestId(document, 'input');

    expect(tempusDominusCalendar()).toBeNull();

    await userEvent.click(input);

    expect(tempusDominusCalendar()).toHaveClass('show');
  });

  it('can receive multiple options', async () => {
    await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-datepicker-options-value='${JSON.stringify({
          allowInputToggle: false,
          dateRange: false,
          debug: false,
          defaultDate: '4 may 2023, 8:31:37',
          display: {
            icons: {
              time: 'fa-solid fa-clock',
              date: 'fa-solid fa-calendar',
              up: 'fa-solid fa-arrow-up',
              down: 'fa-solid fa-arrow-down',
              previous: 'fa-solid fa-chevron-left',
              next: 'fa-solid fa-chevron-right',
              today: 'fa-solid fa-calendar-check',
              clear: 'fa-solid fa-trash',
              close: 'fa-solid fa-xmark',
            },
            sideBySide: false,
            calendarWeeks: false,
            viewMode: 'calendar',
            toolbarPlacement: 'bottom',
            keepOpen: false,
            buttons: {
              today: false,
              clear: false,
              close: false,
            },
            components: {
              calendar: true,
              date: true,
              month: true,
              year: true,
              decades: true,
              clock: true,
              hours: true,
              minutes: true,
              seconds: false,
            },
            inline: false,
            theme: 'light',
          },
          keepInvalid: false,
          localization: {
            format: 'd MMM yyyy, H:mm:ss',
            locale: 'es',
          },
          multipleDates: false,
          multipleDatesSeparator: '; ',
          promptTimeOnDateChange: false,
          promptTimeOnDateChangeTransitionDelay: 200,
          restrictions: {
            minDate: '3 may 2023, 8:31:37',
            maxDate: '28 may 2023, 8:31:37',
            disabledDates: ['6 may 2023, 8:31:37'],
            enabledDates: ['2 may 2023, 8:31:37'],
            daysOfWeekDisabled: [0],
            disabledTimeIntervals: [22],
            disabledHours: [10],
            enabledHours: [3],
          },
          stepping: 1,
          useCurrent: true,
          viewDate: '4 may 2023, 8:31:37',
        })}'
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
      >${innerDatepickerTest}</div>
    `);

    await waitFor(() => {
      expect(document.body).toHaveClass('post-connect-changed-locale');
    });
  });

  it('can be used with related pickers', async () => {
    const { datePicker, datePicker2 } = await startDatepickerTest(`
      <div
        id="datepicker"
        data-controller="datepicker"
        data-td-target-input="nearest"
        data-td-target-toggle="nearest"
        data-datepicker-datepicker-outlet="#datepicker2"
      >${innerDatepickerTest}</div>
      <input
        id="datepicker2"
        type="text"
        data-testid="input2"
        data-controller="datepicker"
      />
    `);

    expect(datePicker.optionsStore.options.restrictions.maxDate).toBeUndefined();
    expect(datePicker2.optionsStore.options.restrictions.minDate).toBeUndefined();

    const firstIcon = getByTestId(document, 'icon');
    const secondInput = getByTestId(document, 'input2');

    await userEvent.click(firstIcon);

    const calendar = tempusDominusCalendar();

    await userEvent.click(calendar.querySelector('.day'));
    await userEvent.click(secondInput);

    const secondCalendar = tempusDominusCalendar();

    await userEvent.click(secondCalendar.querySelector('.day'));

    expect(datePicker.optionsStore.options.restrictions.maxDate).not.toBeUndefined();
    expect(datePicker2.optionsStore.options.restrictions.minDate).not.toBeUndefined();
    expect(
      datePicker.optionsStore.options.restrictions.maxDate >=
        datePicker2.optionsStore.options.restrictions.minDate
    ).toBe(true);
  });
});
