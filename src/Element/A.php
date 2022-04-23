<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Element\Element,
    Node,
    Attribute,
};
use Innmind\Url\Url;
use Innmind\Immutable\Set;

final class A extends Element
{
    private Url $href;

    /**
     * @param Set<Attribute>|null $attributes
     */
    public function __construct(
        Url $href,
        Set $attributes = null,
        Node ...$children,
    ) {
        parent::__construct('a', $attributes, ...$children);
        $this->href = $href;
    }

    public function href(): Url
    {
        return $this->href;
    }
}
