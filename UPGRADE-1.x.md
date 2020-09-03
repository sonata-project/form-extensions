UPGRADE 1.x
===========

## Deprecated `Sonata\Form\Type\BaseDoctrineORMSerializationType`.

This class has been deprecated without replacement.

UPGRADE FROM 1.2 to 1.6
=======================

## Sonata\Form\Type\BasePickerType

Deprecate passing a `RequestStack` object as third parameter, you MUST pass a default locale instead.

UPGRADE FROM 1.0 to 1.2
=======================

## Deprecated EqualType form

If you use `Sonata\Form\Type\EqualType`, you should use `Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType` instead.
