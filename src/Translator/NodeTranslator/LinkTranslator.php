<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
    Exception\InvalidLink,
    Element\Link,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Attribute,
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\{
    Url,
    Exception\Exception,
};
use Innmind\Immutable\Map;

final class LinkTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'link'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);
        $map = $attributes->toMapOf(
            'string',
            Attribute::class,
            static function(Attribute $attribute): \Generator {
                yield $attribute->name() => $attribute;
            },
        );

        if (!$map->contains('href')) {
            throw new MissingHrefAttribute;
        }

        try {
            return new Link(
                Url::of($map->get('href')->value()),
                $map->contains('rel') ?
                    $map->get('rel')->value() : 'related',
                $attributes,
            );
        } catch (Exception $e) {
            throw new InvalidLink;
        }
    }
}
