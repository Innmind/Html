<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element,
    Element\Name,
    Element\Custom,
    Node,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class A implements Custom
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
     * @param Sequence<Node|Element|Custom>|null $children
     */
    public static function of(
        Url $href,
        ?Sequence $attributes = null,
        ?Sequence $children = null,
    ): self {
        return new self(
            $href,
            Element::of(
                Name::of('a'),
                $attributes,
                $children,
            ),
        );
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
