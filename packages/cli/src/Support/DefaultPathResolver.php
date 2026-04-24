<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use Override;
use Symfony\Component\Filesystem\Path;

final readonly class DefaultPathResolver implements PathResolver
{
    public function __construct(
        private string           $cliPackageRoot,
        ?string                  $startDir = null,
        private WorkingDirectory $workingDirectory = new DefaultWorkingDirectory(),
    ) {
    }

    #[Override]
    public function templates(string $subpath = ''): string
    {
        return Path::join($this->cliPackageRoot, 'templates', $subpath);
    }

    #[Override]
    public function cwd(): string
    {
        return $this->workingDirectory->current();
    }

    #[Override]
    public function frontendInitPath(FrontendInitConfig $config): string
    {
        return Path::join($this->cwd(), $config->directory);
    }

    #[Override]
    public function languages(?string $subpath = null): string
    {
        return $this->projectPath($subpath ?? 'languages');
    }

    #[Override]
    public function projectPath(?string $subpath = null): string
    {
        return Path::makeAbsolute($subpath ?? '.', $this->cwd());
    }

    public function buildPath(?string $subpath = null): string
    {
        return Path::makeAbsolute($subpath ?? '.', Path::join($this->cwd(), 'build'));
    }
}
