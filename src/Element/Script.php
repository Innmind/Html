<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element,
    Element\Name,
    Element\Custom,
    Attribute,
    Node,
};
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class Script implements Custom
{
    private function __construct(private Element $element)
    {
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Attribute>|null $attributes
     */
    public static function of(Node $text, ?Sequence $attributes = null): self
    {
        return new self(Element::of(
            Name::of('script'),
            $attributes,
            Sequence::of($text),
        ));
    }

    #[\Override]
    public function normalize(): Element
    {
        return $this->element;
    }
}
