<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Element,
    Element\Custom,
};
use Innmind\Immutable\Sequence;

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
     * @return Sequence<Element|Custom>
     */
    public function __invoke(Document|Node|Element|Custom $node): Sequence
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
     * @return Sequence<Element|Custom>
     */
    private function visitDocument(Document $document): Sequence
    {
        return $document
            ->children()
            ->flatMap(fn($child) => $this($child));
    }

    /**
     * @return Sequence<Element|Custom>
     */
    private function visitNode(Node $node): Sequence
    {
        return Sequence::of();
    }

    /**
     * @return Sequence<Element|Custom>
     */
    private function visitElement(Element $element): Sequence
    {
        /** @var Sequence<Element|Custom>  */
        $elements = Sequence::of();

        if ($element->name()->toString() === $this->name) {
            $elements = ($elements)($element);
        }

        return $element
            ->children()
            ->flatMap(fn($child) => $this($child))
            ->prepend($elements);
    }

    /**
     * @return Sequence<Element|Custom>
     */
    private function visitCustom(Custom $element): Sequence
    {
        /** @var Sequence<Element|Custom>  */
        $elements = Sequence::of();
        $normalized = $element->normalize();

        if ($normalized->name()->toString() === $this->name) {
            $elements = ($elements)($element);
        }

        return $normalized
            ->children()
            ->flatMap(fn($child) => $this($child))
            ->prepend($elements);
    }
}
