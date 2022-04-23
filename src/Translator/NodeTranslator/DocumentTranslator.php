<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Node\Document,
    Exception\InvalidArgumentException,
};
use Innmind\Xml\{
    Node,
    Translator\NodeTranslator,
    Translator\Translator,
    Node\Document\Type,
};

final class DocumentTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate,
    ): Node {
        if (!$node instanceof \DOMDocument) {
            throw new InvalidArgumentException;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress TypeDoesNotContainType
         */
        return new Document(
            $node->doctype ? $this->buildDoctype($node->doctype) : new Type('html'),
            ...($node->childNodes ? $this->buildChildren($node->childNodes, $translate) : []),
        );
    }

    private function buildDoctype(\DOMDocumentType $type): Type
    {
        return new Type(
            $type->name,
            $type->publicId,
            $type->systemId,
        );
    }

    /**
     * @return list<Node>
     */
    private function buildChildren(
        \DOMNodeList $nodes,
        Translator $translate,
    ): array {
        $children = [];

        foreach ($nodes as $child) {
            if ($child->nodeType === \XML_DOCUMENT_TYPE_NODE) {
                continue;
            }

            $children[] = $translate($child);
        }

        return $children;
    }
}
