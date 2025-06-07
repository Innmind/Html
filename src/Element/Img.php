<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element,
    Element\Name,
    Element\Custom,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class Img implements Custom
{
    private Element $element;
    private Url $src;

    private function __construct(Url $src, Element $element)
    {
        $this->element = $element;
        $this->src = $src;
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Attribute>|null $attributes
     */
    public static function of(Url $src, ?Sequence $attributes = null): self
    {
        return new self($src, Element::selfClosing(
            Name::of('img'),
            $attributes,
        ));
    }

    public function src(): Url
    {
        return $this->src;
    }

    #[\Override]
    public function normalize(): Element
    {
        return $this->element->addAttribute(
            Attribute::of('src', $this->src->toString()),
        );
    }
}
