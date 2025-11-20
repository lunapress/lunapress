<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\RenderTranslate;

use LunaPress\Wp\I18n\Support\Concerns\HasDomain;
use LunaPress\Wp\I18n\Support\Concerns\HasText;
use LunaPress\Wp\I18nContracts\RenderTranslate\IRenderTranslateFunction;

defined('ABSPATH') || exit;

final class RenderTranslate implements IRenderTranslateFunction
{
    use HasText;
    use HasDomain;

    public function rawArgs(): array
    {
        return [
            $this->getText(),
            $this->getDomain()
        ];
    }

    public function executeWithArgs(array $args): void
    {
        // phpcs:ignore WordPress.Security.EscapeOutput.UnsafePrintingFunction, WordPress.WP.I18n
        _e(...$args);
    }
}
