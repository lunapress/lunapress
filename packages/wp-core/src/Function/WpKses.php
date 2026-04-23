<?php

declare(strict_types=1);

namespace LunaPress\Wp\Core\Function;

final class WpKses
{
    /**
     * @param string[] $allowedProtocols
     */
    public function __invoke(
        string $content,
        array|string $allowedHtml,
        array $allowedProtocols = [],
    ): string
    {
        return wp_kses(
            $content,
            $allowedHtml,
            $allowedProtocols,
        );
    }
}
