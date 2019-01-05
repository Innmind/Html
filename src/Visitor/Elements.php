<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\InvalidArgumentException;
use Innmind\Xml\{
    Node,
    Element
};
use Innmind\Immutable\{
    Set,
    SetInterface
};

class Elements
{
    private $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException;
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
