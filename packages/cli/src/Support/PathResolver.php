<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

use LunaPress\Cli\Frontend\Init\FrontendInitConfig;
use Override;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

final readonly class PathResolver implements IPathResolver
{
    public function __construct(
        private string $cliPackageRoot,
        ?string                   $startDir = null,
        private IWorkingDirectory $workingDirectory = new WorkingDirectory(),
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
}
