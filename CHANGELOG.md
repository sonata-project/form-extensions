# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
