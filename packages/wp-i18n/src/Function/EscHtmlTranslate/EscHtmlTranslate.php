<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscHtmlTranslate;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\EscHtmlTranslate\IEscHtmlTranslateFunction;

final class EscHtmlTranslate implements IEscHtmlTranslateFunction
{
    use HasText;
    use HasDomain;

    public function rawArgs(): array
    {
        return [
            $this->getText(),
            $this->getDomain(),
        ];
    }

    public function executeWithArgs(array $args): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return esc_html__(...$args);
    }
}
