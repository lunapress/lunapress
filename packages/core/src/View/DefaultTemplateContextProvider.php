<?php

declare(strict_types=1);

namespace LunaPress\Core\View;

use LunaPress\FoundationContracts\View\TemplateContextProvider;

final readonly class DefaultTemplateContextProvider implements TemplateContextProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return [];
    }
}
