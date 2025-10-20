<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

use LunaPress\Cli\Frontend\Init\FrontendInitConfig;

defined('ABSPATH') || exit;

interface IPathResolver
{
    public function cwd(): string;
    public function templates(string $subpath = ''): string;
    public function frontendInitPath(FrontendInitConfig $config): string;
}
