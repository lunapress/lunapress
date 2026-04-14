<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscHtmlRender;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\EscHtmlRender\IEscHtmlRenderFunction;

defined('ABSPATH') || exit;

final class EscHtmlRender implements IEscHtmlRenderFunction
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
        esc_html_e(...$args);
    }
}
