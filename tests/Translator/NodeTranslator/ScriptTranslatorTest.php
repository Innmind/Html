<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Translator\NodeTranslator;

use Innmind\Html\{
    Translator,
    Element\Script,
};
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ScriptTranslatorTest extends TestCase
{
    public function testTranslate()
    {
        $dom = new \DOMDocument;
        $dom->loadHTML('<script type="text/javascript">var foo = 42;</script>');

        $script = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($script) => $script,
            static fn() => null,
        );

        $this->assertInstanceOf(Script::class, $script);
        $script = $script->normalize();
        $this->assertSame(
            '<script type="text/javascript">var foo = 42;</script>'."\n",
            $script->asContent()->toString(),
        );
        $this->assertCount(1, $script->attributes());
        $this->assertSame(
            'text/javascript',
            $script->attribute('type')->match(
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

        $script = Translator::new()(
            $dom->childNodes->item(1)->childNodes->item(0)->childNodes->item(0),
        )->match(
            static fn($script) => $script,
            static fn() => null,
        );

        $this->assertInstanceOf(Script::class, $script);
        $script = $script->normalize();
        $this->assertSame(
            '<script></script>'."\n",
            $script->asContent()->toString(),
        );
        $this->assertCount(0, $script->attributes());
        $this->assertCount(1, $script->children());
    }
}
