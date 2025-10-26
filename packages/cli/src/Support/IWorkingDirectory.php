<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

interface IWorkingDirectory
{
    public function current(): string;
}
