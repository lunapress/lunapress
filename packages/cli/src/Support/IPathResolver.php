<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

defined('ABSPATH') || exit;

interface IPathResolver
{
    public function cwd(): string;
    public function templates(string $subpath = ''): string;
}
