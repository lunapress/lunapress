<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrContextTranslate;

use LunaPress\Wp\I18n\Trait\HasContext;
use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\EscAttrContextTranslate\IEscAttrContextTranslateFunction;

defined('ABSPATH') || exit;

final class EscAttrContextTranslate implements IEscAttrContextTranslateFunction
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

    public function executeWithArgs(array $args): string
    {
        // phpcs:ignore WordPress.WP.I18n
        return esc_attr_x(...$args);
    }
}
