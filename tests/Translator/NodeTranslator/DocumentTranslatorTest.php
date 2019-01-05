<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\DocumentTranslator,
    Node\Document,
    Exception\InvalidArgumentException,
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
            new DocumentTranslator
        );
    }

    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html><body></body>');

        $node = (new DocumentTranslator)(
            $document,
            new Translator(NodeTranslators::defaults())
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

        $node = (new DocumentTranslator)(
            $document,
            new Translator(NodeTranslators::defaults())
        );

        $this->assertSame(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">',
            (string) $node->type()
        );
    }

    public function testTranslateWithoutChildren()
    {
        $document = new \DOMDocument;
        $document->loadHtml('<!DOCTYPE html>');

        $node = (new DocumentTranslator)(
            $document,
            new Translator(NodeTranslators::defaults())
        );

        $this->assertFalse($node->hasChildren());
    }

    public function testThrowWhenInvalidNode()
    {
        $document = new \DOMDocument;
        $document->loadXML('<foo></foo>');

        $this->expectException(InvalidArgumentException::class);

        (new DocumentTranslator)(
            $document->childNodes->item(0),
            new Translator(NodeTranslators::defaults())
        );
    }
}
