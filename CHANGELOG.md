# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.17.0](https://github.com/sonata-project/form-extensions/compare/1.16.1...1.17.0) - 2022-06-14
### Deprecated
- [[#359](https://github.com/sonata-project/form-extensions/pull/359)] Passing an array as first element of `ErrorElement::addViolation()` ([@VincentLanglet](https://github.com/VincentLanglet))

### Fixed
- [[#365](https://github.com/sonata-project/form-extensions/pull/365)] Datepicker positionning bug when bottom viewport is to small (https://github.com/Eonasdan/tempus-dominus/pull/1203) ([@Geekimo](https://github.com/Geekimo))

### Removed
- [[#364](https://github.com/sonata-project/form-extensions/pull/364)] Support of Symfony 5.3 ([@franmomu](https://github.com/franmomu))

## [1.16.1](https://github.com/sonata-project/form-extensions/compare/1.16.0...1.16.1) - 2022-05-24
### Changed
- [[#357](https://github.com/sonata-project/form-extensions/pull/357)] Fix compatibility of `bootstrap-datetimepicker` with jQuery 3 ([@franmomu](https://github.com/franmomu))

## [1.16.0](https://github.com/sonata-project/form-extensions/compare/1.15.2...1.16.0) - 2022-05-14
### Removed
- [[#351](https://github.com/sonata-project/form-extensions/pull/351)] Remove compatibility with PHP 7.3. ([@jordisala1991](https://github.com/jordisala1991))

## [1.15.2](https://github.com/sonata-project/form-extensions/compare/1.15.1...1.15.2) - 2022-05-14
### Fixed
- [[#347](https://github.com/sonata-project/form-extensions/pull/347)] Fix compatibility with PHP 7.3. ([@jordisala1991](https://github.com/jordisala1991))

## [1.15.1](https://github.com/sonata-project/form-extensions/compare/1.15.0...1.15.1) - 2022-05-14
### Fixed
- [[#341](https://github.com/sonata-project/form-extensions/pull/341)] Fix compatibility with PHP 7.3. ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.15.0](https://github.com/sonata-project/form-extensions/compare/1.14.0...1.15.0) - 2022-05-12
### Added
- [[#336](https://github.com/sonata-project/form-extensions/pull/336)] Add momentjs locale canonicalizer. ([@jordisala1991](https://github.com/jordisala1991))

## [1.14.0](https://github.com/sonata-project/form-extensions/compare/1.13.1...1.14.0) - 2022-05-08
### Added
- [[#329](https://github.com/sonata-project/form-extensions/pull/329)] Add webpack configuration to compile the date picker scripts used by the form types. ([@jordisala1991](https://github.com/jordisala1991))

## [1.13.1](https://github.com/sonata-project/form-extensions/compare/1.13.0...1.13.1) - 2022-02-24
### Fixed
- [[#313](https://github.com/sonata-project/form-extensions/pull/313)] Fixed `InlineConstraint` usage annotation due do unitialized constraint groups. ([@houssemzi](https://github.com/houssemzi))

## [1.13.0](https://github.com/sonata-project/form-extensions/compare/1.12.4...1.13.0) - 2022-02-12
### Deprecated
- [[#304](https://github.com/sonata-project/form-extensions/pull/304)] Sonata\Form\Serializer\BaseSerializerHandler ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#304](https://github.com/sonata-project/form-extensions/pull/304)] Sonata\Form\Serializer\SerializerHandlerInterface ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#304](https://github.com/sonata-project/form-extensions/pull/304)] Sonata\Form\Type\BaseDoctrineORMSerializationType ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#304](https://github.com/sonata-project/form-extensions/pull/304)] Serializer config key ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.12.4](https://github.com/sonata-project/form-extensions/compare/1.12.3...1.12.4) - 2021-12-04
### Fixed
- [[#296](https://github.com/sonata-project/form-extensions/pull/296)] Support of serializable entities field without group set. ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.12.3](https://github.com/sonata-project/form-extensions/compare/1.12.2...1.12.3) - 2021-11-25
### Fixed
- [[#292](https://github.com/sonata-project/form-extensions/pull/292)] Submitting `CollectionType` in Symfony 6 ([@franmomu](https://github.com/franmomu))

## [1.12.2](https://github.com/sonata-project/form-extensions/compare/1.12.1...1.12.2) - 2021-11-25
### Fixed
- [[#290](https://github.com/sonata-project/form-extensions/pull/290)] Using `CollectionType` with Symfony 6 ([@franmomu](https://github.com/franmomu))

## [1.12.1](https://github.com/sonata-project/form-extensions/compare/1.12.0...1.12.1) - 2021-10-26
### Fixed
- [[#280](https://github.com/sonata-project/form-extensions/pull/280)] Fixed support for Symfony 6, adding a return type on `getContainerExtensionClass` ([@jordisala1991](https://github.com/jordisala1991))

## [1.12.0](https://github.com/sonata-project/form-extensions/compare/1.11.0...1.12.0) - 2021-10-17
### Added
- [[#272](https://github.com/sonata-project/form-extensions/pull/272)] `@method` annotation to the ErrorElement class ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.11.0](https://github.com/sonata-project/form-extensions/compare/1.10.0...1.11.0) - 2021-09-22
### Added
- [[#258](https://github.com/sonata-project/form-extensions/pull/258)] Added support for Symfony 6 ([@jordisala1991](https://github.com/jordisala1991))

## [1.10.0](https://github.com/sonata-project/form-extensions/compare/1.9.0...1.10.0) - 2021-09-08
### Fixed
- [[#234](https://github.com/sonata-project/form-extensions/pull/234)] Fixed deprecation on `StubTranslation` by adding `getLocale()` method ([@jordisala1991](https://github.com/jordisala1991))

### Removed
- [[#234](https://github.com/sonata-project/form-extensions/pull/234)] Removed Support for Symfony 5.1 and 5.2 ([@jordisala1991](https://github.com/jordisala1991))

## [1.9.0](https://github.com/sonata-project/form-extensions/compare/1.8.2...1.9.0) - 2021-02-15
### Added
- [[#202](https://github.com/sonata-project/form-extensions/pull/202)] Hungarian translation for keys `date_range_start` and `date_range_end` ([@fracsi](https://github.com/fracsi))

## [1.8.2](https://github.com/sonata-project/form-extensions/compare/1.8.1...1.8.2) - 2021-02-09
### Fixed
- [[#194](https://github.com/sonata-project/form-extensions/pull/194)] Php version constraint ([@greg0ire](https://github.com/greg0ire))

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
