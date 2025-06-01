<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator\NodeTranslator\ScriptTranslator,
    Element\Script,
};
use Innmind\Xml\Translator\{
    Translator,
    NodeTranslators,
    NodeTranslator,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ScriptTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            ScriptTranslator::of(),
        );
    }

    public function testReturnNothingWhenNotExpectedElement()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<body></body>');

        $result = ScriptTranslator::of()(
            $dom->childNodes->item(1),
            Translator::of(
                NodeTranslators::defaults(),
            )
        );

        $this->assertNull($result->match(
            static fn($node) => $node,
            static fn() => null,
        ));
    }

    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<script type="text/javascript">var foo = 42;</script>');

        $script = ScriptTranslator::of()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($script) => $script,
            static fn() => null,
        );

        $this->assertInstanceOf(Script::class, $script);
        $this->assertSame('var foo = 42;', $script->content());
        $this->assertCount(1, $script->attributes());
        $this->assertSame(
            'text/javascript',
            $script->attributes()->get('type')->match(
                static fn($attribute) => $attribute->value(),
                static fn() => null,
            ),
        );
        $this->assertCount(1, $script->children());
    }

    public function testTranslateWithoutCode()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<script></script>');

        $script = ScriptTranslator::of()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
            Translator::of(
                NodeTranslators::defaults(),
            )
        )->match(
            static fn($script) => $script,
            static fn() => null,
        );

        $this->assertInstanceOf(Script::class, $script);
        $this->assertSame('', $script->content());
        $this->assertCount(0, $script->attributes());
        $this->assertCount(1, $script->children());
    }
}
