<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Functions\ContextTranslate;

use LunaPress\Wp\I18n\Trait\HasContext;
use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\ContextTranslate\IContextTranslateFunction;

defined('ABSPATH') || exit;

final class ContextTranslate implements IContextTranslateFunction
{
    use HasDomain;
    use HasText;
    use HasContext;

    public function rawArgs(): array
    {
        return [
            $this->getText(),
            $this->getContext(),
            $this->getDomain(),
        ];
    }

    public function executeWithArgs(array $args): ?string
    {
        // phpcs:ignore WordPress.WP.I18n
        return _x(...$args);
    }
}
