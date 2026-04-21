<?php

declare(strict_types=1);

namespace LunaPress\Cli\Support;

use Symfony\Component\Process\Process;

final class ProcessFactory implements IProcessFactory
{
    public function create(array $command): Process
    {
        $process = new Process($command);
        $process->setTimeout(null);
        return $process;
    }
}
