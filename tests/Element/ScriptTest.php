<?php
declare(strict_types = 1);

namespace Tests\Innmind\Html\Element;

use Innmind\Html\Element\Script;
use Innmind\Xml\{
    Element,
    Node,
    Attribute,
};
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function testInterface()
    {
        $script = Script::of(
            Node::text('foo'),
        );

        $this->assertInstanceOf(Element\Custom::class, $script);
        $script = $script->normalize();
        $this->assertSame(
            '<script>foo</script>'."\n",
            $script->asContent()->toString(),
        );
        $this->assertCount(1, $script->children());
    }

    public function testWithAttributes()
    {
        $script = Script::of(
            Node::text('foo'),
            Sequence::of(
                $attribute = Attribute::of('foo', 'bar'),
            ),
        );

        $this->assertSame($attribute, $script->normalize()->attribute('foo')->match(
            static fn($attribute) => $attribute,
            static fn() => null,
        ));
    }
}
