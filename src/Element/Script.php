<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Node,
    Attribute,
    Element\Element,
    Node\Text,
};
use Innmind\Immutable\Set;

final class Script extends Element
{
    /**
     * @param Set<Attribute>|null $attributes
     */
    public function __construct(Text $text, Set $attributes = null)
    {
        parent::__construct('script', $attributes, $text);
    }
}
