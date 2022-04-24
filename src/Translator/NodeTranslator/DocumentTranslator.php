<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Translator\NodeTranslator,
    Translator\Translator,
    Node\Document\Type,
};
use Innmind\Immutable\{
    Maybe,
    Sequence,
};

/**
 * @psalm-immutable
 */
final class DocumentTranslator implements NodeTranslator
{
    public function __invoke(
        \DOMNode $node,
        Translator $translate,
    ): Maybe {
        /**
         * @psalm-suppress ArgumentTypeCoercion
         * @var Maybe<Node>
         */
        return Maybe::just($node)
            ->filter(static fn($node) => $node instanceof \DOMDocument)
            ->flatMap(
                fn(\DOMDocument $node) => $this
                    ->buildChildren($node->childNodes, $translate)
                    ->map(fn($children) => new Document(
                        Maybe::of($node->doctype)
                            ->flatMap($this->buildDoctype(...))
                            ->match(
                                static fn($type) => $type,
                                static fn() => Type::of('html'),
                            ),
                        ...$children->toList(),
                    )),
            );
    }

    /**
     * @return Maybe<Type>
     */
    private function buildDoctype(\DOMDocumentType $type): Maybe
    {
        return Type::maybe(
            $type->name,
            $type->publicId,
            $type->systemId,
        );
    }

    /**
     * @return Maybe<Sequence<Node>>
     */
    private function buildChildren(
        \DOMNodeList $nodes,
        Translator $translate,
    ): Maybe {
        /** @var Maybe<Sequence<Node>> */
        $children = Maybe::just(Sequence::of());

        foreach ($nodes as $child) {
            if ($child->nodeType === \XML_DOCUMENT_TYPE_NODE) {
                continue;
            }

            $children = $children->flatMap(
                static fn($children) => $translate($child)->map(
                    static fn($child) => ($children)($child),
                ),
            );
        }

        return $children;
    }
}
