<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Symfony\Component\Process\Process;

interface ProcessFactory
{
    /**
     * @param array<string> $command
     */
    public function create(array $command): Process;
}
