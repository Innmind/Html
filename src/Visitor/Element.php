<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\{
    ElementNotFound,
    InvalidArgumentException
};
use Innmind\Xml\{
    Node,
    Element as ElementInterface
};

class Element
{
    private $name;

    public function __construct(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException;
        }

        $this->name = $name;
    }

    public function __invoke(Node $node): ElementInterface
    {
        if (
            $node instanceof ElementInterface &&
            $node->name() === $this->name
        ) {
            return $node;
        }

        if ($node->hasChildren()) {
            foreach ($node->children() as $child) {
                try {
                    return $this($child);
                } catch (ElementNotFound $e) {
                    //pass
                }
            }
        }

        throw new ElementNotFound;
    }
}
