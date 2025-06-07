# Changelog

## [Unreleased]

### Added

- `Innmind\Html\Translator`
- `Innmind\Html\Reader\Reader::new()`

### Changed

- Requires `innmind/xml:~8.0`
- Requires `innmind/filesystem:~8.1`
- `Innmind\Html\Element\*` classes now implement `Innmind\Xml\Element\Custom`
- `Innmind\Html\Node\Document` no longer implement any interface
- `Innmind\Html\Reader\Reader` now return an `Innmind\Immutable\Attempt`
- `Innmind\Html\Visitor\Elements` now return a `Innmind\Immutable\Sequence`

### Removed

- `Innmind\Html\Translator\*`
- `Innmind\Html\Node\Document::content()`
- `Innmind\Html\Node\Document::toString()`
- `Innmind\Html\Reader\Reader::default()`
- `Innmind\Html\Reader\Reader::of()`

### Fixed

- PHP `8.4` deprecations

## 6.4.0 - 2024-06-26

### Changed

- Requires `innmind/xml:~7.7`
- `Document` children Sequence is no longer forced to be lazy, it depends on the kind of Sequence you use when building it

## 6.3.0 - 2023-12-02

### Added

- Support for `symfony/dom-crawler:~7.0`

### Changed

- Requires `symfony/dom-crawler:~6.3`

## 6.2.0 - 2023-11-01

### Changed

- Requires `innmind/xml:~7.6`
- Requires `innmind/filesystem:~7.1`

### Removed

- Support for PHP `8.1`

## 6.1.1 - 2023-04-17

### Fixed

- Fix the `toString()` of `A`, `Base`, `Img` and `Link` that where missing attributes

## 6.1.0 - 2023-04-17

### Added

- `Innmind\Html\Node\Document` now implements `Innmind\Xml\AsContent`

### Changed

- `symfony/dom-crawler:~6.1` is now longer supported as it has invalid behaviour with html5
