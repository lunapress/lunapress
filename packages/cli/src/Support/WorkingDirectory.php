<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

interface WorkingDirectory
{
    public function current(): string;
}
