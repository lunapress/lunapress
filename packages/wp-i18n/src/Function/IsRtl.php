<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class IsRtl
{
    public function __invoke(): bool
    {
        return is_rtl();
    }
}
