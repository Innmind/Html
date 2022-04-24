<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\DocumentTranslator,
    Node\Document,
};
use Innmind\Xml\{
    Translator\NodeTranslator,
    Translator\Translator,
    Translator\NodeTranslators,
};
use PHPUnit\Framework\TestCase;

class DocumentTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            DocumentTranslator::of(),
        );
    }

    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html><body></body>');

        $node = DocumentTranslator::of()(
            $document,
            Translator::of(NodeTranslators::defaults())
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame('html', $node->type()->name());
        $this->assertCount(1, $node->children());
        $this->assertSame('<html><body/></html>', $node->content());
    }

    public function testTranslateWithoutDoctype()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!--foo-->');

        $node = DocumentTranslator::of()(
            $document,
            Translator::of(NodeTranslators::defaults())
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

        $node = DocumentTranslator::of()(
            $document,
            Translator::of(NodeTranslators::defaults())
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertTrue($node->children()->empty());
    }

    public function testReturnNothingWhenInvalidNode()
    {
        $document = new \DOMDocument;
        $document->loadXML('<foo></foo>');

        $result = DocumentTranslator::of()(
            $document->childNodes->item(0),
            Translator::of(NodeTranslators::defaults())
        );

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }
}
