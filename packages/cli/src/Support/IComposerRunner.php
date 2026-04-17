<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

use LunaPress\Cli\Support\Exceptions\ComposerExecutionException;

interface IComposerRunner
{
    /**
     * @param string $workingDirectory
     * @param callable(string $type, string $buffer): void|null $onOutput
     * @throws ComposerExecutionException
     * @return void
     */
    public function installNoDev(string $workingDirectory, ?callable $onOutput = null): void;
}
