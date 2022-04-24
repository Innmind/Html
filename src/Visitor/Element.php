<?php
declare(strict_types = 1);

namespace Innmind\Html\Visitor;

use Innmind\Xml\{
    Node,
    Element as ElementInterface,
};
use Innmind\Immutable\Maybe;

/**
 * @psalm-immutable
 */
final class Element
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
     * @return Maybe<ElementInterface>
     */
    public function __invoke(Node $node): Maybe
    {
        if (
            $node instanceof ElementInterface &&
            $node->name() === $this->name
        ) {
            return Maybe::just($node);
        }

        /** @var Maybe<ElementInterface> */
        return $node->children()->reduce(
            Maybe::nothing(),
            function(Maybe $element, Node $child): Maybe {
                return $element->otherwise(fn() => $this($child));
            },
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

    /**
     * @psalm-pure
     */
    public static function head(): self
    {
        return new self('head');
    }

    /**
     * @psalm-pure
     */
    public static function body(): self
    {
        return new self('body');
    }
}
