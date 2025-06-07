<?php
declare(strict_types = 1);

namespace Innmind\Html;

use Innmind\Xml\{
    Node,
    Element,
    Element\Custom,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\Attempt;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @psalm-immutable
 */
final class Reader
{
    private function __construct(private Translator $translate)
    {
    }

    /**
     * @return Attempt<Document|Node|Element|Custom>
     */
    public function __invoke(Content $html): Attempt
    {
        /** @psalm-suppress ImpureMethodCall */
        $firstNode = (new Crawler($html->toString(), useHtml5Parser: false))->getNode(0);

        if (!$firstNode instanceof \DOMNode) {
            /** @var Attempt<Document|Node|Element|Custom> */
            return Attempt::error(new \RuntimeException('Failed to parse html content'));
        }

        /** @psalm-suppress RedundantCondition */
        while ($firstNode->parentNode instanceof \DOMNode) {
            $firstNode = $firstNode->parentNode;
        }

        return ($this->translate)($firstNode);
    }

    public static function new(): self
    {
        return new self(
            Translator::new(),
        );
    }
}
