<?php
declare(strict_types = 1);

namespace Innmind\Html;

use Innmind\Xml\{
    Reader as ReaderInterface,
    Translator\Translator as NodeTranslator,
    Translator\NodeTranslators,
};
use Innmind\Immutable\Map;

/**
 * @param Map<int, NodeTranslator>|null $translators
 */
function bootstrap(Map $translators = null): ReaderInterface
{
    return new Reader\Reader(
        new NodeTranslator(
            $translators ?? NodeTranslators::defaults()->merge(
                Translator\NodeTranslators::defaults(),
            ),
        ),
    );
}
