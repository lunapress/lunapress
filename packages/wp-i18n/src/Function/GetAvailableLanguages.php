<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function;

final readonly class GetAvailableLanguages
{
    /**
     * @return string[]
     */
    public function __invoke(?string $dir = null): array
    {
        return get_available_languages($dir);
    }
}
