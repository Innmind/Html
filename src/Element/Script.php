<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Xml\{
    NodeInterface,
    Element\Element,
    Node\Text
};
use Innmind\Immutable\{
    Map,
    MapInterface
};

final class Script extends Element
{
    public function __construct(Text $text, MapInterface $attributes = null)
    {
        parent::__construct(
            'script',
            $attributes,
            (new Map('int', NodeInterface::class))
                ->put(0, $text)
        );
    }
}
