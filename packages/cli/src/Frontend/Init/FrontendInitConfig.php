<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use LunaPress\Cli\Frontend\FrontendFramework;
use LunaPress\Cli\Frontend\PackageManager;

defined('ABSPATH') || exit;

final readonly class FrontendInitConfig
{
    public const bool DEFAULT_USE_TAILWIND = true;
    public const string DEFAULT_DIRECTORY  = 'frontend';

    public function __construct(
        public FrontendFramework $framework,
        public bool $useTailwind,
        public PackageManager $packageManager,
        public string $directory,
    ) {
    }


    public function toArray(): array
    {
        return [
            'framework' => $this->framework,
            'useTailwind' => $this->useTailwind,
            'packageManager' => $this->packageManager,
            'directory' => $this->directory,
        ];
    }
}
