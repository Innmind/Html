<?php
declare(strict_types = 1);

namespace Innmind\Html\Translator;

use Innmind\Html\Translator\NodeTranslator\{
    DocumentTranslator,
    ElementTranslator,
    ATranslator,
    BaseTranslator,
    ImgTranslator,
    LinkTranslator,
    ScriptTranslator,
};
use Innmind\Xml\Translator\{
    NodeTranslator,
    NodeTranslator\ElementTranslator as GenericTranslator,
};
use Innmind\Immutable\Map;

final class NodeTranslators
{
    /**
     * @return Map<int, NodeTranslator>
     */
    public static function defaults(): Map
    {
        /**
         * @psalm-suppress MixedArgumentTypeCoercion
         * @psalm-suppress InvalidArgument
         * @var Map<int, NodeTranslator>
         */
        return Map::of(
            [\XML_HTML_DOCUMENT_NODE, new DocumentTranslator],
            [
                \XML_ELEMENT_NODE,
                new ElementTranslator(
                    GenericTranslator::of(),
                    Map::of(
                        ['a', new ATranslator],
                        ['base', new BaseTranslator],
                        ['img', new ImgTranslator],
                        ['link', new LinkTranslator],
                        ['script', new ScriptTranslator],
                    ),
                ),
            ],
        );
    }
}
