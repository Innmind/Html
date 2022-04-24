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
         * @psalm-suppress InvalidArgument
         * @var Map<int, NodeTranslator>
         */
        return Map::of(
            [\XML_HTML_DOCUMENT_NODE, DocumentTranslator::of()],
            [
                \XML_ELEMENT_NODE,
                ElementTranslator::of(
                    GenericTranslator::of(),
                    Map::of(
                        ['a', ATranslator::of()],
                        ['base', BaseTranslator::of()],
                        ['img', ImgTranslator::of()],
                        ['link', LinkTranslator::of()],
                        ['script', ScriptTranslator::of()],
                    ),
                ),
            ],
        );
    }
}
