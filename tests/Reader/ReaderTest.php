<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Reader;

use Innmind\Html\{
    Reader\Reader,
    Node\Document,
};
use Innmind\Xml\{
    Reader as ReaderInterface,
    Node\Document as XmlDocument,
};
use Innmind\Filesystem\File\Content;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    private $read;

    public function setUp(): void
    {
        $this->read = Reader::default();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            ReaderInterface::class,
            $this->read,
        );
    }

    public function testReadSimple()
    {
        $html = <<<HTML
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        foo
    </body>
</html>
HTML;
        $node = ($this->read)(
            Content::ofString($html),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );
        $expected = <<<HTML
        <!DOCTYPE html>
        <html>
            <head/>
            <body>
                foo
            </body>
        </html>
        HTML;

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame($expected, $node->toString());
    }

    public function testReadFullPage()
    {
        $node = ($this->read)(
            Content::ofString(\file_get_contents('fixtures/lemonde.html')),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Document::class, $node);
    }

    public function testReadScreenOnline()
    {
        $node = ($this->read)(Content::ofString(\file_get_contents(
            'fixtures/www.screenonline.org.uk_tv_id_560180_.html',
        )))->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(XmlDocument::class, $node);
    }

    public function testReturnNothingWhenEmptyStream()
    {
        $this->assertNull(
            ($this->read)(Content::ofString(''))->match(
                static fn($node) => $node,
                static fn() => null,
            ),
        );
    }
}
