<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Node;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Node\Document\Type,
    Node\Text,
    Element\Element,
};
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Node::class,
            Document::of(Type::of('html')),
        );
    }

    public function testType()
    {
        $type = Type::of('html');

        $this->assertSame(
            $type,
            (Document::of($type))->type(),
        );
    }

    public function testWithoutChildren()
    {
        $document = Document::of(Type::of('html'));

        $this->assertInstanceOf(Sequence::class, $document->children());
        $this->assertTrue($document->children()->empty());
    }

    public function testWithChildren()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of($child = $this->createMock(Node::class)),
        );

        $this->assertSame($child, $document->children()->first()->match(
            static fn($node) => $node,
            static fn() => null,
        ));
        $this->assertFalse($document->children()->empty());
    }

    public function testContent()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(
                    'html',
                    null,
                    Sequence::of(Text::of('wat')),
                ),
            ),
        );

        $this->assertSame('<html>wat</html>', $document->content());
    }

    public function testCast()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(
                    'html',
                    null,
                    Sequence::of(Text::of('wat')),
                ),
            ),
        );

        $this->assertSame(
            '<!DOCTYPE html>'."\n".'<html>wat</html>',
            $document->toString(),
        );
    }

    public function testPrependChild()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of('foo'),
                Element::of('bar'),
                Element::of('baz'),
            ),
        );

        $document2 = $document->prependChild(
            $node = $this->createMock(Node::class),
        );

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertNotSame($document->children(), $document2->children());
        $this->assertCount(3, $document->children());
        $this->assertCount(4, $document2->children());
        $this->assertSame(
            $node,
            $document2->children()->get(0)->match(
                static fn($node) => $node,
                static fn() => null,
            ),
        );
        $this->assertEquals(
            $document->children()->get(0),
            $document2->children()->get(1),
        );
        $this->assertEquals(
            $document->children()->get(1),
            $document2->children()->get(2),
        );
        $this->assertEquals(
            $document->children()->get(2),
            $document2->children()->get(3),
        );
    }

    public function testAopendChild()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of('foo'),
                Element::of('bar'),
                Element::of('baz'),
            ),
        );

        $document2 = $document->appendChild(
            $node = $this->createMock(Node::class),
        );

        $this->assertNotSame($document, $document2);
        $this->assertInstanceOf(Document::class, $document2);
        $this->assertSame($document->type(), $document2->type());
        $this->assertNotSame($document->children(), $document2->children());
        $this->assertCount(3, $document->children());
        $this->assertCount(4, $document2->children());
        $this->assertEquals(
            $document->children()->get(0),
            $document2->children()->get(0),
        );
        $this->assertEquals(
            $document->children()->get(1),
            $document2->children()->get(1),
        );
        $this->assertEquals(
            $document->children()->get(2),
            $document2->children()->get(2),
        );
        $this->assertSame(
            $node,
            $document2->children()->get(3)->match(
                static fn($node) => $node,
                static fn() => null,
            ),
        );
    }
}
