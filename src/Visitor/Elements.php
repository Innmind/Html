<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\DomainException;
use Innmind\Xml\{
    Node,
    Element,
};
use Innmind\Immutable\{
    Set,
    Str,
};

/**
 * @psalm-immutable
 */
final class Elements
{
    private string $name;

    private function __construct(string $name)
    {
        if (Str::of($name)->empty()) {
            throw new DomainException;
        }

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
     */
    public static function of(string $name): self
    {
        return new self($name);
    }
}
