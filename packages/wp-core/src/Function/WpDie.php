<?php

declare(strict_types=1);

namespace LunaPress\Wp\Core\Function;

use LunaPress\FoundationContracts\Support\WpFunction\IWpCaster;
use LunaPress\FoundationContracts\Support\WpFunction\WpArray;
use LunaPress\Wp\CoreContracts\DTO\IWpError;
use LunaPress\Wp\CoreContracts\DTO\WpDieArgs;

final readonly class WpDie
{
    public function __construct(
        private IWpCaster $caster,
    ) {
    }

    public function __invoke(
        IWpError|string $message = '',
        int|string $title = '',
        WpDieArgs|WpArray $args = WpArray::Empty
    ): never
    {
        // @TODO: normalize $message
        wp_die(
            $message,
            $title,
            $this->caster->value($args)
        );
    }
}
