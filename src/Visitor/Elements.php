<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Element,
    Element\Custom,
};
use Innmind\Immutable\Set;

/**
 * @psalm-immutable
 */
final class Elements
{
    /** @var non-empty-string */
    private string $name;

    /**
     * @param non-empty-string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Set<Element|Custom>
     */
    public function __invoke(Document|Node|Element|Custom $node): Set
    {
        return match (true) {
            $node instanceof Document => $this->visitDocument($node),
            $node instanceof Node => $this->visitNode($node),
            $node instanceof Element => $this->visitElement($node),
            $node instanceof Custom => $this->visitCustom($node),
        };
    }

    /**
     * @psalm-pure
     *
     * @param non-empty-string $name
     */
    public static function of(string $name): self
    {
        return new self($name);
    }

    /**
     * @return Set<Element|Custom>
     */
    private function visitDocument(Document $document): Set
    {
        return $document
            ->children()
            ->flatMap(fn($child) => $this($child)->unsorted())
            ->toSet();
    }

    /**
     * @return Set<Element|Custom>
     */
    private function visitNode(Node $node): Set
    {
        return Set::of();
    }

    /**
     * @return Set<Element|Custom>
     */
    private function visitElement(Element $element): Set
    {
        /** @var Set<Element|Custom>  */
        $elements = Set::of();

        if ($element->name()->toString() === $this->name) {
            $elements = ($elements)($element);
        }

        return $elements->merge(
            $element
                ->children()
                ->flatMap(fn($child) => $this($child)->unsorted())
                ->toSet(),
        );
    }

    /**
     * @return Set<Element|Custom>
     */
    private function visitCustom(Custom $element): Set
    {
        /** @var Set<Element|Custom>  */
        $elements = Set::of();
        $normalized = $element->normalize();

        if ($normalized->name()->toString() === $this->name) {
            $elements = ($elements)($element);
        }

        return $elements->merge(
            $normalized
                ->children()
                ->flatMap(fn($child) => $this($child)->unsorted())
                ->toSet(),
        );
    }
}
