<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttribute,
    Element\A,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children,
};
use Innmind\Url\Url;

final class ATranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'a'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);

        if (!$attributes->contains('href')) {
            throw new MissingHrefAttribute;
        }

        return new A(
            Url::fromString($attributes->get('href')->value()),
            $attributes,
            (new Children($translate))($node)
        );
    }
}
