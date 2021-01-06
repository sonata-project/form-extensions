# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.8.1](https://github.com/sonata-project/form-extensions/compare/1.8.0...1.8.1) - 2021-01-06
### Fixed
- [[#192](https://github.com/sonata-project/form-extensions/pull/192)] User Deprecated: Using a custom format when the "html5" option of `Symfony\Component\Form\Extension\Core\Type\DateTimeType` is enabled is deprecated since Symfony 4.3 and will lead to an exception in 5.0. ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#192](https://github.com/sonata-project/form-extensions/pull/192)] User Deprecated: Using a custom format when the "html5" option of `Symfony\Component\Form\Extension\Core\Type\DateType` is enabled is deprecated since Symfony 4.3 and will lead to an exception in 5.0. ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.8.0](https://github.com/sonata-project/form-extensions/compare/1.7.1...1.8.0) - 2021-01-04
### Added
- [[#185](https://github.com/sonata-project/form-extensions/pull/185)] Support for PHP8 ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.7.1](https://github.com/sonata-project/form-extensions/compare/1.7.0...1.7.1) - 2020-11-22
### Changed
- [[#169](https://github.com/sonata-project/form-extensions/pull/169)] Cast form name to string for symfony5.0 compatibility ([@phiamo](https://github.com/phiamo))

## [1.7.0](https://github.com/sonata-project/form-extensions/compare/1.6.0...1.7.0) - 2020-11-16
### Added
- [[#151](https://github.com/sonata-project/form-extensions/pull/151)] Support for
  instances of `\DateTimeImmutable` in form options "dp_min_date" and "dp_max_date"
  at `BasePickerType` ([@phansys](https://github.com/phansys))

### Changed
- [[#111](https://github.com/sonata-project/form-extensions/pull/111)] Bumped Symfony
  to 5.1 ([@franmomu](https://github.com/franmomu))

### Fixed
- [[#129](https://github.com/sonata-project/form-extensions/pull/129)] Fixed triggering
  a deprecation for EqualType only when it is used ([@franmomu](https://github.com/franmomu))

## [1.6.0](https://github.com/sonata-project/form-extensions/compare/1.5.0...1.6.0) - 2020-08-05
### Deprecated
- [[#109](https://github.com/sonata-project/form-extensions/pull/109)]
  Deprecated passing a `RequestStack` object to `BasePickerType` as third
parameter, the default locale should be passed instead.
([@franmomu](https://github.com/franmomu))

### Fixed
- [[#109](https://github.com/sonata-project/form-extensions/pull/109)] Fixed
  using `BasePickerType` without a request.
([@franmomu](https://github.com/franmomu))
- [[#107](https://github.com/sonata-project/form-extensions/pull/107)] Replace
  `spaceless` deprecated tag with `apply` tag and `spaceless` filter.
([@franmomu](https://github.com/franmomu))

## [1.5.0](https://github.com/sonata-project/form-extensions/compare/1.4.0...1.5.0) - 2020-06-27
### Fixed
- [[#101](https://github.com/sonata-project/form-extensions/pull/101)]
  AbstractWidgetTestCase now works then testing with symfony/symfony on other
bundles ([@jordisala1991](https://github.com/jordisala1991))

### Removed
- [[#102](https://github.com/sonata-project/form-extensions/pull/102)] Support
  for Symfony 3.4 ([@jordisala1991](https://github.com/jordisala1991))

## [1.4.0](https://github.com/sonata-project/form-extensions/compare/1.3.0...1.4.0) - 2020-06-04
### Added
- add `Sonata\Form\Bridge\Symfony\Bundle\SonataFormBundle`

### Deprecated
- deprecated `Sonata\Form\Bridge\Symfony\Bundle\SonataFormBundle`. Use
  `Sonata\Form\Bridge\Symfony\Bundle\SonataFormBundle` instead.

### Removed
- return type hints in `Sonata\Form\Serializer\BaseSerializerHandler::serializeObjectToId()`
- return type hints in `Sonata\Form\Serializer\SerializerHandlerIntertface::getType()`
- return type hints in `Sonata\Form\Type\BaseStatusType::configureOptions()`
- color translations and templates

## [1.3.0](https://github.com/sonata-project/form-extensions/compare/1.2.0...1.3.0) - 2020-04-10
### Added
- Added `Sonata\Form\Serializer\BaseSerializerHandler`
- Added `Sonata\Form\Serializer\BaseSerializerHandlerInterface`

## [1.2.0](https://github.com/sonata-project/form-extensions/compare/1.1.2...1.2.0) - 2020-03-21
### Added
- Added support for `twig/twig:^3.0`
- Deprecate `EqualType` in favor of SonataAdmin `EqualOperatorType`

## [1.1.2](https://github.com/sonata-project/form-extensions/compare/1.1.1...1.1.2) - 2019-12-21
### Changed
- Make `AbstractWidgetTestCase` environment even more extendable

## [1.1.1](https://github.com/sonata-project/form-extensions/compare/1.1.0...1.1.1) - 2019-12-21
### Changed
- Make `AbstractWidgetTestCase` environment extendable

## [1.1.0](https://github.com/sonata-project/form-extensions/compare/1.0.0...1.1.0) - 2019-12-06
### Added
- Added Support for Symfony 5 packages

### Removed
- Remove `BaseDoctrineORMSerializationType::setDefaultOptions`
