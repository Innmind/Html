<?php
declare(strict_types = 1);

namespace Innmind\Html\Reader;

use Innmind\Html\Exception\RuntimeException;
use Innmind\Xml\{
    Reader as ReaderInterface,
    Node,
    Translator\Translator,
};
use Innmind\Stream\Readable;
use Symfony\Component\DomCrawler\Crawler;

final class Reader implements ReaderInterface
{
    private Translator $translate;

    private function __construct(Translator $translate)
    {
        $this->translate = $translate;
    }

    public function __invoke(Readable $html): Node
    {
        $firstNode = (new Crawler($html->toString()))->getNode(0);

        if (!$firstNode instanceof \DOMNode) {
            throw new RuntimeException('No html found');
        }

        /** @psalm-suppress RedundantCondition */
        while ($firstNode->parentNode instanceof \DOMNode) {
            $firstNode = $firstNode->parentNode;
        }

        return ($this->translate)($firstNode);
    }

    public static function of(Translator $translate): self
    {
        return new self($translate);
    }
}
