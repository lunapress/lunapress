<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Override;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

defined('ABSPATH') || exit;

final readonly class PathResolver implements IPathResolver
{
    private string $root;

    public function __construct(
        ?string                   $startDir = null,
        private IWorkingDirectory $workingDirectory = new WorkingDirectory(),
    ) {
        $this->root = $this->findPackageRoot($startDir ?? __DIR__);
    }

    #[Override]
    public function templates(string $subpath = ''): string
    {
        return Path::join($this->root, 'templates', $subpath);
    }

    #[Override]
    public function cwd(): string
    {
        return $this->workingDirectory->current();
    }

    private function findPackageRoot(string $dir): string
    {
        $filesystem = new Filesystem();
        $current    = $dir;

        while ($current !== Path::getRoot($current)) {
            if ($filesystem->exists(Path::join($current, 'composer.json'))) {
                return $current;
            }
            $current = Path::getDirectory($current);
        }

        throw new RuntimeException('Unable to resolve LunaPress CLI package root.');
    }
}
