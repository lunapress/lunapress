<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrTranslate;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\EscAttrTranslate\IEscAttrTranslateFunction;

defined('ABSPATH') || exit;

final class EscAttrTranslate implements IEscAttrTranslateFunction
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

    public function executeWithArgs(array $args): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return esc_attr__(...$args);
    }
}
