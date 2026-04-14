<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\ContextNoopPluralTranslate;

use LunaPress\Wp\I18n\Entity\NoopedPlural;
use LunaPress\Wp\I18n\Trait\HasContext;
use LunaPress\Wp\I18n\Trait\HasNoopPlural;
use LunaPress\Wp\I18n\Trait\HasOptionalDomain;
use LunaPress\Wp\I18nContracts\Entity\INoopedPlural;
use LunaPress\Wp\I18nContracts\Function\ContextNoopPluralTranslate\IContextNoopPluralTranslateFunction;

defined('ABSPATH') || exit;

final class ContextNoopPluralTranslate implements IContextNoopPluralTranslateFunction
{
    use HasNoopPlural;
    use HasContext;
    use HasOptionalDomain;

    public function rawArgs(): array
    {
        return [
            $this->getSingle(),
            $this->getPlural(),
            $this->getContext(),
            $this->getDomain()
        ];
    }

    public function executeWithArgs(array $args): INoopedPlural
    {
        // phpcs:ignore WordPress.WP.I18n
        $noop = _nx_noop(...$args);

        return new NoopedPlural(
            $noop['singular'],
            $noop['plural'],
            $noop['context'],
            $noop['domain']
        );
    }
}
