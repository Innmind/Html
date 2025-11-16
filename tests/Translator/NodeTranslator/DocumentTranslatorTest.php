<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Document,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class DocumentTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $document = \Dom\HTMLDocument::createFromString('<!DOCTYPE html><body></body>');

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame('html', $node->type()->name());
        $this->assertSame(1, $node->children()->size());
        $this->assertSame(
            <<<HTML
            <!DOCTYPE html>
            <html>
                <head/>
                <body/>
            </html>

            HTML,
            $node->asContent()->toString(),
        );
    }

    public function testTranslateWithoutDoctype()
    {
        $document = \Dom\HTMLDocument::createFromString(
            '<!--foo-->',
            \LIBXML_NOERROR,
        );

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertSame(
            '<!DOCTYPE html>',
            $node->type()->toString(),
        );
    }

    public function testTranslateWithoutChildren()
    {
        $document = \Dom\HTMLDocument::createFromString(
            '<!DOCTYPE html>',
            \LIBXML_HTML_NOIMPLIED | \LIBXML_NOERROR,
        );

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertTrue($node->children()->empty());
    }
}
