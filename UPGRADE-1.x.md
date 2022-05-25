UPGRADE 1.x
===========

UPGRADE FROM 1.16 to 1.17
=========================

## Sonata\Form\Validator\ErrorElement

Deprecate passing an array as first parameter of `addViolation`
```php
$errorElement->addViolation(['Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR']);
```

You MUST call
```php
$errorElement->addViolation('Foo error message', ['bar_param' => 'bar_param_lvalue'], 'BAR');
```
instead.

UPGRADE FROM 1.6 to 1.7
=======================

## Sonata\Form\Type\BasePickerType

Added support for instances of `\DateTimeImmutable` in form options "dp_min_date" and "dp_max_date".

UPGRADE FROM 1.2 to 1.6
=======================

## Sonata\Form\Type\BasePickerType

Deprecate passing a `RequestStack` object as third parameter, you MUST pass a default locale instead.

UPGRADE FROM 1.0 to 1.2
=======================

## Deprecated EqualType form

If you use `Sonata\Form\Type\EqualType`, you should use `Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType` instead.
