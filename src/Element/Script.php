<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    Node,
    Element\Element,
    Node\Text,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class Script extends Element
{
    public function __construct(Text $text, MapInterface $attributes = null)
    {
        parent::__construct(
            'script',
            $attributes,
            (new Map('int', Node::class))
                ->put(0, $text)
        );
    }
}
