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
    Translator\NodeTranslator\Visitor\Attributes,
};
use Innmind\Url\{
    Url,
    Exception\ExceptionInterface,
};

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

        if (!$attributes->contains('href')) {
            throw new MissingHrefAttribute;
        }

        try {
            return new Link(
                Url::fromString($attributes->get('href')->value()),
                $attributes->contains('rel') ?
                    $attributes->get('rel')->value() : 'related',
                $attributes
            );
        } catch (ExceptionInterface $e) {
            throw new InvalidLink;
        }
    }
}
