UPGRADE 2.0
===========

## Remove canonicalize extension

It is no longer needed with the new Date Picker library, the locale is automatically detected in the browser.

## Upgrade Date Picker

Since the begining, Sonata was using Eonasdan Bootstrap Datepicker in its version 3.1.3 to provide date picker capabilities.

Starting from 2.0 of `form-extensions`, the date picker library has been updated to latest version of
Eonasdan Tempus Dominus. It is the successor of Bootstrap Datepicker,
and its options are not compatible
with the previous version (but they look similar).

Previously all the options were configured using `dp_` prefix, now they are configured using `datepicker_options`.

This affects all the `Picker` types, including `DatePickerType`, `DateTimePickerType`, `DateRangePickerType` and `DateTimeRangePickerType`.

For example, the following code:

```php
$form->add('date', DatePickerType::class, [
    'dp_use_current' => false,
    'dp_min_date' => '2017-01-01',
    'dp_max_date' => '2017-12-31',
]);
```

Should be replaced by:

```php
$form->add('date', DatePickerType::class, [
    'datepicker_options' => [
        'useCurrent' => false,
        'restrictions' => [
            'minDate' => '2017-01-01',
            'maxDate' => '2017-12-31',
        ],
    ],
]);
```

All the options accepted by Tempus Dominus are available via `datepicker_options` and are well
documented on the official [docs](https://getdatepicker.com/6/options/).

You can also take a look at Sonata documentation for more information.

## Deprecations

All the deprecated code introduced on 1.x is removed on 2.0.

Please read [1.x](https://github.com/sonata-project/form-extensions/tree/1.x) upgrade guides for more information.

See also the [diff code](https://github.com/sonata-project/form-extensions/compare/1.x...2.0.0).
