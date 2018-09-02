<?php
declare(strict_types = 1);

namespace Innmind\Html;

use Innmind\Xml\{
    ReaderInterface,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
};
use Innmind\Immutable\MapInterface;

/**
 * @param MapInterface<int, NodeTranslatorInterface>|null $translators
 */
function bootstrap(MapInterface $translators = null): ReaderInterface
{
    return new Reader\Reader(
        new NodeTranslator(
            $translators ?? NodeTranslators::defaults()->merge(
                Translator\NodeTranslators::defaults()
            )
        )
    );
}
