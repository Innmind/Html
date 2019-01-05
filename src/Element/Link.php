<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Html\Exception\DomainException;
use Innmind\Xml\Element\SelfClosingElement;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    MapInterface,
    Str,
};

final class Link extends SelfClosingElement
{
    private $href;
    private $relationship;

    public function __construct(
        UrlInterface $href,
        string $relationship,
        MapInterface $attributes = null
    ) {
        if (Str::of($relationship)->empty()) {
            throw new DomainException;
        }

        parent::__construct('link', $attributes);
        $this->href = $href;
        $this->relationship = $relationship;
    }

    public function href(): UrlInterface
    {
        return $this->href;
    }

    public function relationship(): string
    {
        return $this->relationship;
    }
}
