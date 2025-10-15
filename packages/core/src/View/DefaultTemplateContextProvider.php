<?php
declare(strict_types=1);

namespace LunaPress\Core\View;

use LunaPress\FoundationContracts\View\ITemplateContextProvider;

defined('ABSPATH') || exit;

final readonly class DefaultTemplateContextProvider implements ITemplateContextProvider
{
    public function getContext(): array
    {
        return [];
    }
}
