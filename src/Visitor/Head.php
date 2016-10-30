<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\HeadNotFoundException;
use Innmind\Xml\{
    NodeInterface,
    ElementInterface
};

final class Head
{
    public function __invoke(NodeInterface $node): ElementInterface
    {
        if (
            $node instanceof ElementInterface &&
            $node->name() === 'head'
        ) {
            return $node;
        }

        if ($node->hasChildren()) {
            foreach ($node->children() as $child) {
                try {
                    return $this($child);
                } catch (HeadNotFoundException $e) {
                    //pass
                }
            }
        }

        throw new HeadNotFoundException;
    }
}
