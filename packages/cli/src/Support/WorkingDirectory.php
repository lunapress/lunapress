<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

defined('ABSPATH') || exit;

final readonly class WorkingDirectory implements IWorkingDirectory
{
    public function current(): string
    {
        return getcwd();
    }
}
