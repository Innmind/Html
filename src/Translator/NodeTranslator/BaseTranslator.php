<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Exception\MissingHrefAttributeException,
    Element\Base
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes
};
use Innmind\Url\Url;

final class BaseTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'base'
        ) {
            throw new InvalidArgumentException;
        }

        $attributes = (new Attributes)($node);

        if (!$attributes->contains('href')) {
            throw new MissingHrefAttributeException;
        }

        return new Base(
            Url::fromString($attributes->get('href')->value()),
            $attributes
        );
    }
}
