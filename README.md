# HTML

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Html/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Html/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Html/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Html/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Html/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Html/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Html/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Html/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Html/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Html/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Html/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Html/build-status/develop) |

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
