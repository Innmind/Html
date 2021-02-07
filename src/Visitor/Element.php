<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Html\Exception\{
    ElementNotFound,
    DomainException,
};
use Innmind\Xml\{
    Node,
    Element as ElementInterface,
};
use Innmind\Immutable\Str;

final class Element
{
    private string $name;

    public function __construct(string $name)
    {
        if (Str::of($name)->empty()) {
            throw new DomainException;
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

        $element = $node->children()->reduce(
            null,
            function(?ElementInterface $element, Node $child): ?ElementInterface {
                if ($element instanceof ElementInterface) {
                    return $element;
                }

                try {
                    return $this($child);
                } catch (ElementNotFound $e) {
                    return null;
                }
            },
        );

        if ($element instanceof ElementInterface) {
            return $element;
        }

        throw new ElementNotFound;
    }

    public static function head(): self
    {
        return new self('head');
    }

    public static function body(): self
    {
        return new self('body');
    }
}
