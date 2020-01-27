# HTML

| `develop` |
|-----------|
| [![codecov](https://codecov.io/gh/Innmind/Html/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/Html) |
| [![Build Status](https://github.com/Innmind/Html/workflows/CI/badge.svg)](https://github.com/Innmind/Html/actions?query=workflow%3ACI) |

This library is an extension of [`innmind/xml`](https://packagist.org/packages/innmind/xml) to support working properly with html as a node tree.

## Installation

```sh
composer require innmind/html
```

## Usage

```php
use function Innmind\Html\bootstrap;
use Innmind\Stream\Readable\Stream;

$read = bootstrap();

$html = $read(
    new Stream(fopen('https://github.com/', 'r'))
);
```

Here `$html` is an instance of [`Document`](src/Node/Document.php).

## Extract some elements of the tree

This library provides some visitors to extract elements out of the dom tree, the example below show you how to extract all the `h1` elements of a tree:

```php
use Innmind\Html\Visitor\Elements;

$h1s = (new Elements('h1'))($html);
```

Here `$h1s` is a set of `Element` which are all `h1` elements.

Here's the full list of visitors you have access to:

* [`Body`](src/Visitor/Body.php)
* [`Head`](src/Visitor/Head.php)
* [`Element`](src/Visitor/Element.php)
* [`Elements`](src/Visitor/Elements.php)
