# Changelog

## 6.1.1 - 2023-04-17

### Fixed

- Fix the `toString()` of `A`, `Base`, `Img` and `Link` that where missing attributes

## 6.1.0 - 2023-04-17

### Added

- `Innmind\Html\Node\Document` now implements `Innmind\Xml\AsContent`

### Changed

- `symfony/dom-crawler:~6.1` is now longer supported as it has invalid behaviour with html5
