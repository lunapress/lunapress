<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use LunaPress\Cli\Frontend\Init\FrontendInitConfig;

interface PathResolver
{
    public function cwd(): string;
    public function templates(string $subpath = ''): string;
    public function languages(?string $subpath = null): string;
    public function frontendInitPath(FrontendInitConfig $config): string;
    public function projectPath(?string $subpath = null): string;
    public function buildPath(?string $subpath = null): string;
}
