<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Override;

defined('ABSPATH') || exit;

final readonly class WorkingDirectory implements IWorkingDirectory
{
    #[Override]
    public function current(): string
    {
        return getcwd();
    }
}
