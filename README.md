# HTML

[![Build Status](https://github.com/innmind/html/workflows/CI/badge.svg?branch=master)](https://github.com/innmind/html/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/innmind/html/branch/develop/graph/badge.svg)](https://codecov.io/gh/innmind/html)
[![Type Coverage](https://shepherd.dev/github/innmind/html/coverage.svg)](https://shepherd.dev/github/innmind/html)

This library is an extension of [`innmind/xml`](https://packagist.org/packages/innmind/xml) to support working properly with html as a node tree.

**Important**: you must use [`vimeo/psalm`](https://packagist.org/packages/vimeo/psalm) to make sure you use this library correctly.

## Installation

```sh
composer require innmind/html
```

## Usage

```php
use Innmind\Html\Reader\Reader;
use Innmind\Xml\Node;
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\Maybe;

$read = Reader::default();

$html = $read(
    Content::ofString(\file_get_contents('https://github.com/')),
); // Maybe<Node>
```

## Extract some elements of the tree

This library provides some visitors to extract elements out of the dom tree, the example below show you how to extract all the `h1` elements of a tree:

```php
use Innmind\Html\Visitor\Elements;

$h1s = Elements::of('h1')($html);
```

Here `$h1s` is a set of `Element` which are all `h1` elements.

Here's the full list of visitors you have access to:

* [`Element`](src/Visitor/Element.php)
* [`Elements`](src/Visitor/Elements.php)
