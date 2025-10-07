<?php
declare(strict_types=1);

namespace LunaPress\Frontend;

use LunaPress\CoreContracts\Package\IPackage;
use LunaPress\CoreContracts\Plugin\IContext;

defined('ABSPATH') || exit;

final readonly class FrontendPackage implements IPackage
{
    public function activate(IContext $context): void
    {
    }

    public function boot(): void
    {
    }

    public function deactivate(IContext $context): void
    {
    }

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
