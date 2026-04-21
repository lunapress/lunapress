<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Override;
use function getcwd;

final readonly class WorkingDirectory implements IWorkingDirectory
{
    #[Override]
    public function current(): string
    {
        return getcwd();
    }
}
