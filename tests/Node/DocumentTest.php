<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Node;

use Innmind\Html\Node\Document;
use Innmind\Xml\{
    Node,
    Document\Type,
    Element,
    Element\Name,
};
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    public function testType()
    {
        $type = Type::of('html');

        $this->assertSame(
            $type,
            Document::of($type)->type(),
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
            Sequence::of($child = Node::text('')),
        );

        $this->assertSame($child, $document->children()->first()->match(
            static fn($node) => $node,
            static fn() => null,
        ));
        $this->assertFalse($document->children()->empty());
    }

    public function testCast()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(
                    Name::of('html'),
                    null,
                    Sequence::of(Node::text('wat')),
                ),
            ),
        );

        $this->assertSame(
            '<!DOCTYPE html>'."\n".'<html>wat</html>'."\n",
            $document->asContent()->toString(),
        );
    }

    public function testPrependChild()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(Name::of('foo')),
                Element::of(Name::of('bar')),
                Element::of(Name::of('baz')),
            ),
        );

        $document2 = $document->prependChild(
            $node = Node::text(''),
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

    public function testAppendChild()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(Name::of('foo')),
                Element::of(Name::of('bar')),
                Element::of(Name::of('baz')),
            ),
        );

        $document2 = $document->appendChild(
            $node = Node::text(''),
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

    public function testAsContent()
    {
        $document = Document::of(
            Type::of('html'),
            Sequence::of(
                Element::of(
                    Name::of('html'),
                    null,
                    Sequence::of(Element::of(
                        Name::of('body'),
                        null,
                        Sequence::of(Node::text('wat')),
                    )),
                ),
            ),
        );

        $this->assertSame(
            <<<HTML
            <!DOCTYPE html>
            <html>
                <body>wat</body>
            </html>

            HTML,
            $document->asContent()->toString(),
        );
    }
}
