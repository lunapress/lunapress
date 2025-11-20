<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Translate;

use LunaPress\Wp\I18n\Support\Concerns\HasDomain;
use LunaPress\Wp\I18n\Support\Concerns\HasText;
use LunaPress\Wp\I18nContracts\Translate\ITranslateFunction;

defined('ABSPATH') || exit;

final class Translate implements ITranslateFunction
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
        return __(...$args);
    }
}
