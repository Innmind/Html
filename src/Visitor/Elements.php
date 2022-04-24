<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Xml\{
    Node,
    Element,
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
     * @return Set<Element>
     */
    public function __invoke(Node $node): Set
    {
        /** @var Set<Element> */
        $elements = Set::of();

        if (
            $node instanceof Element &&
            $node->name() === $this->name
        ) {
            $elements = ($elements)($node);
        }

        /** @var Set<Element> */
        return $node->children()->reduce(
            $elements,
            fn(Set $elements, Node $child): Set => $elements->merge($this($child)),
        );
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
}
