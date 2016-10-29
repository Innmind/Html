<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Element\Script
};
use Innmind\Xml\{
    Translator\NodeTranslatorInterface,
    Translator\NodeTranslator,
    NodeInterface,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children,
    Node\Text
};
use Innmind\Url\Url;

final class ScriptTranslator implements NodeTranslatorInterface
{
    public function translate(
        \DOMNode $node,
        NodeTranslator $translator
    ): NodeInterface {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'script'
        ) {
            throw new InvalidArgumentException;
        }

        $text = '';

        if ($node->firstChild) {
            $text = $translator
                ->translate($node->firstChild)
                ->content();
        }

        return new Script(
            new Text($text),
            (new Attributes)($node)
        );
    }
}
