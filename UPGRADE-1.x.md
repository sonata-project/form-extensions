UPGRADE 1.x
===========

## Sonata\Form\Type\BasePickerType

Deprecate passing a `RequestStack` object as third parameter, you MUST pass a default locale instead.

## Deprecated EqualType form

If you use `Sonata\Form\Type\EqualType`, you should use `Sonata\AdminBundle\Form\Type\Operator\EqualOperatorType` instead.
