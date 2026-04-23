<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class GetUserLocale
{
    public function __invoke(int $userId = 0): string
    {
        return get_user_locale($userId);
    }
}
