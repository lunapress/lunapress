<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\EscAttrRender;

use LunaPress\Wp\I18n\Trait\HasDomain;
use LunaPress\Wp\I18n\Trait\HasText;
use LunaPress\Wp\I18nContracts\Function\EscAttrRender\IEscAttrRenderFunction;

final class EscAttrRender implements IEscAttrRenderFunction
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

    public function executeWithArgs(array $args): void
    {
        // phpcs:ignore WordPress.WP.I18n
        esc_attr_e(...$args);
    }
}
