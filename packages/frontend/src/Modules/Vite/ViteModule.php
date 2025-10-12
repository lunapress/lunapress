<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite;

use LunaPress\Foundation\Module\AbstractModule;

defined('ABSPATH') || exit;

final class ViteModule extends AbstractModule
{
    /**
     * @inheritDoc
     */
    public function subscribers(): array
    {
        return [];
    }
}
