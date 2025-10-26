<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\PackageManager;

final readonly class FrontendInitConfig
{
    public const bool DEFAULT_USE_TAILWIND = true;
    public const string DEFAULT_DIRECTORY  = 'frontend';

    private string $name;

    public function __construct(
        public FrontendFramework $framework,
        public bool $useTailwind,
        public PackageManager $packageManager,
        public string $directory,
    ) {
        $this->name = $this->sanitizeName($directory);
    }


    public function toTemplateArray(): array
    {
        return [
            'framework' => $this->framework->value,
            'useTailwind' => $this->useTailwind,
            'packageManager' => $this->packageManager->value,
            'directory' => $this->directory,
            'name' => $this->name,
            'packageManagerVersion' => $this->getPackageManagerConfig(),

            'isReact' => $this->framework === FrontendFramework::React,
            'isPnpm' => $this->packageManager === PackageManager::Pnpm,
        ];
    }

    private function getPackageManagerConfig(): string
    {
        return match ($this->packageManager) {
            PackageManager::Pnpm => 'pnpm@10.19.0',
        };
    }

    private function sanitizeName(string $name): string
    {
        return preg_replace('/[^a-z-]+/i', '', strtolower($name)) ?: '';
    }
}
