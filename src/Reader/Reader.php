<?php
declare(strict_types = 1);

namespace Innmind\Html\Reader;

use Innmind\Xml\{
    ReaderInterface,
    NodeInterface,
    Translator\NodeTranslator
};
use Innmind\Stream\Readable;
use Symfony\Component\DomCrawler\Crawler;

final class Reader implements ReaderInterface
{
    private $translator;

    public function __construct(NodeTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function read(Readable $html): NodeInterface
    {
        $firstNode = (new Crawler((string) $html))->getNode(0);

        while ($firstNode->parentNode instanceof \DOMNode) {
            $firstNode = $firstNode->parentNode;
        }

        return $this->translator->translate($firstNode);
    }
}
