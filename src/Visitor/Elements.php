<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\InvalidArgumentException;
use Innmind\Xml\{
    NodeInterface,
    ElementInterface
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
     * @return SetInterface<ElementInterface>
     */
    public function __invoke(NodeInterface $node): SetInterface
    {
        $elements = new Set(ElementInterface::class);

        if (
            $node instanceof ElementInterface &&
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
