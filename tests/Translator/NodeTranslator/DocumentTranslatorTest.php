<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Node\Document,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class DocumentTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html><body></body>');

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame('html', $node->type()->name());
        $this->assertCount(1, $node->children());
        $this->assertSame(
            <<<HTML
            <!DOCTYPE html>
            <html>
                <body/>
            </html>

            HTML,
            $node->asContent()->toString(),
        );
    }

    public function testTranslateWithoutDoctype()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!--foo-->');

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertSame(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            $node->type()->toString(),
        );
    }

    public function testTranslateWithoutChildren()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html>');

        $node = Translator::new()(
            $document,
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertTrue($node->children()->empty());
    }
}
