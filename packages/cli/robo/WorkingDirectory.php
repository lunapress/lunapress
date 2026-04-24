<?php

declare(strict_types=1);

namespace LunaPress\Cli\Robo;

use LunaPress\Cli\Support\WorkingDirectory;

final readonly class WorkingDirectory implements WorkingDirectory
{
    public function current(): string
    {
        return __DIR__ . '/../../../';
    }
}
