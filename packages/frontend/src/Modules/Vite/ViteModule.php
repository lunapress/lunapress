<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite;

use LunaPress\FoundationContracts\Module\IModule;

defined('ABSPATH') || exit;

final readonly class ViteModule implements IModule
{
    /**
     * @inheritDoc
     */
    public function subscribers(): array
    {
        return [];
    }
}
