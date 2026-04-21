<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Symfony\Component\Process\Process;

interface IProcessFactory
{
    /**
     * @param array<string> $command
     */
    public function create(array $command): Process;
}
