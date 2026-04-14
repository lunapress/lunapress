<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\RenderContextTranslate;

use LunaPress\Wp\I18n\Trait\HasContext;
use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\RenderContextTranslate\IRenderContextTranslateFunction;

defined('ABSPATH') || exit;

final class RenderContextTranslate implements IRenderContextTranslateFunction
{
    use HasText;
    use HasContext;
    use HasDomain;

    public function rawArgs(): array
    {
        return [
            $this->getText(),
            $this->getContext(),
            $this->getDomain()
        ];
    }

    public function executeWithArgs(array $args): void
    {
        // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction, WordPress.WP.I18n
        _ex(...$args);
    }
}
