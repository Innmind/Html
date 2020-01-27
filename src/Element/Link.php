<?php
declare(strict_types = 1);

namespace Innmind\Html\Element;

use Innmind\Html\Exception\DomainException;
use Innmind\Xml\Element\SelfClosingElement;
use Innmind\Url\Url;
use Innmind\Immutable\{
    Set,
    Str,
};

final class Link extends SelfClosingElement
{
    private Url $href;
    private string $relationship;

    public function __construct(
        Url $href,
        string $relationship,
        Set $attributes = null
    ) {
        if (Str::of($relationship)->empty()) {
            throw new DomainException;
        }

        parent::__construct('link', $attributes);
        $this->href = $href;
        $this->relationship = $relationship;
    }

    public function href(): Url
    {
        return $this->href;
    }

    public function relationship(): string
    {
        return $this->relationship;
    }
}
