<?php

declare(strict_types=1);

namespace LunaPress\Core\View;

use LunaPress\FoundationContracts\View\ITemplateContextProvider;

final readonly class DefaultTemplateContextProvider implements ITemplateContextProvider
{
    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return [];
    }
}
