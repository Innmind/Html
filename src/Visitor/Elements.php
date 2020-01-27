<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\DomainException;
use Innmind\Xml\{
    Node,
    Element,
};
use Innmind\Immutable\{
    SetInterface,
    Set,
    Str,
};

class Elements
{
    private string $name;

    public function __construct(string $name)
    {
        if (Str::of($name)->empty()) {
            throw new DomainException;
        }

        $this->name = $name;
    }

    /**
     * @return SetInterface<Element>
     */
    public function __invoke(Node $node): SetInterface
    {
        $elements = new Set(Element::class);

        if (
            $node instanceof Element &&
            $node->name() === $this->name
        ) {
            $elements = $elements->add($node);
        }

        if ($node->hasChildren()) {
            foreach ($node->children() as $child) {
                $elements = $elements->merge(
                    $this($child)
                );
            }
        }

        return $elements;
    }
}
