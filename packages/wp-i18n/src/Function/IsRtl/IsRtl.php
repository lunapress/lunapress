<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\IsRtl;

use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFunction;

final class IsRtl implements IIsRtlFunction
{
    public function rawArgs(): array
    {
        return [];
    }

    public function executeWithArgs(array $args): bool
    {
        return is_rtl();
    }
}
