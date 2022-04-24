<?php
declare(strict_types = 1);

namespace Innmind\Html\Reader;

use Innmind\Html\Translator\NodeTranslators as HtmlNodeTranslators;
use Innmind\Xml\{
    Reader as ReaderInterface,
    Node,
    Translator\Translator,
    Translator\NodeTranslators,
};
use Innmind\Filesystem\File\Content;
use Innmind\Immutable\Maybe;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @psalm-immutable
 */
final class Reader implements ReaderInterface
{
    private Translator $translate;

    private function __construct(Translator $translate)
    {
        $this->translate = $translate;
    }

    public function __invoke(Content $html): Maybe
    {
        /** @psalm-suppress ImpureMethodCall */
        $firstNode = (new Crawler($html->toString()))->getNode(0);

        if (!$firstNode instanceof \DOMNode) {
            /** @var Maybe<Node> */
            return Maybe::nothing();
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

    public static function default(): self
    {
        return new self(
            Translator::of(
                NodeTranslators::defaults()->merge(
                    HtmlNodeTranslators::defaults(),
                ),
            ),
        );
    }
}
