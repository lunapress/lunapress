<?php
declare(strict_types=1);

namespace LunaPress\Cli\Robo;

use LunaPress\Cli\Support\IWorkingDirectory;

defined('ABSPATH') || exit;

final readonly class WorkingDirectory implements IWorkingDirectory
{
    public function current(): string
    {
        return __DIR__ . '/../../../';
    }
}
