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
        $content = $html->toString();

        if ($content === '') {
            return Attempt::error(new \RuntimeException('Empty content'));
        }

        try {
            return ($this->translate)(\Dom\HTMLDocument::createFromString(
                $content,
                \LIBXML_HTML_NOIMPLIED | \LIBXML_NOERROR,
            ));
        } catch (\Throwable $e) {
            return Attempt::error($e);
        }
    }

    public static function new(): self
    {
        return new self(
            Translator::new(),
        );
    }
}
