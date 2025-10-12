<?php
declare(strict_types=1);

namespace LunaPress\Frontend;

use LunaPress\Foundation\Package\AbstractPackage;

defined('ABSPATH') || exit;

final class FrontendPackage extends AbstractPackage
{
    /**
     * @inheritDoc
     */
    public function getModules(): array
    {
        return [
            Modules\Vite\ViteModule::class,
        ];
    }

    public static function getDiPath(): ?string
    {
        return __DIR__ . '/di.php';
    }
}
