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
use Innmind\Stream\Readable\Stream;
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
            Content\OfStream::of(Stream::ofContent($html)),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );
        $expected = <<<HTML
<!DOCTYPE html>
<html><head/><body>
        foo
    </body></html>
HTML;

        if (\PHP_OS === 'Darwin') {
            // don't know why there is a difference between linux and macOS
            $expected = <<<HTML
            <!DOCTYPE html>
            <html>
                <head/>
                <body>
                    foo
                </body>
            </html>
            HTML;
        }

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame($expected, $node->toString());
    }

    public function testReadFullPage()
    {
        $node = ($this->read)(
            Content\OfStream::of(Stream::of(
                \fopen('fixtures/lemonde.html', 'r'),
            )),
        )->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(Document::class, $node);
    }

    public function testReadScreenOnline()
    {
        $node = ($this->read)(Content\OfStream::of(Stream::of(\fopen(
            'fixtures/www.screenonline.org.uk_tv_id_560180_.html',
            'r',
        ))))->match(
            static fn($node) => $node,
            static fn() => null,
        );

        $this->assertInstanceOf(XmlDocument::class, $node);
    }

    public function testReturnNothingWhenEmptyStream()
    {
        $this->assertNull(
            ($this->read)(Content\OfStream::of(Stream::ofContent('')))->match(
                static fn($node) => $node,
                static fn() => null,
            ),
        );
    }
}
