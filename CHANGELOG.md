# Changelog

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
