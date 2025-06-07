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
final class Base implements Custom
{
    private Element $element;
    private Url $href;

    private function __construct(Url $href, Element $element)
    {
        $this->element = $element;
        $this->href = $href;
    }

    /**
     * @psalm-pure
     *
     * @param Sequence<Attribute>|null $attributes
     */
    public static function of(Url $href, ?Sequence $attributes = null): self
    {
        return new self($href, Element::selfClosing(
            Name::of('base'),
            $attributes,
        ));
    }

    public function href(): Url
    {
        return $this->href;
    }

    #[\Override]
    public function normalize(): Element
    {
        return $this->element->addAttribute(
            Attribute::of('href', $this->href->toString()),
        );
    }
}
