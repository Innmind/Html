<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Exception\InvalidArgumentException,
    Element\Script,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Node,
    Translator\NodeTranslator\Visitor\Attributes,
    Translator\NodeTranslator\Visitor\Children,
    Node\Text,
};

final class ScriptTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate,
    ): Node {
        if (
            !$node instanceof \DOMElement ||
            $node->tagName !== 'script'
        ) {
            throw new InvalidArgumentException;
        }

        $text = '';

        if ($node->firstChild) {
            $text = $translate($node->firstChild)->content();
        }

        return new Script(
            new Text($text),
            (new Attributes)($node),
        );
    }
}
