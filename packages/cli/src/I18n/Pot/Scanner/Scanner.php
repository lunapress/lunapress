<?php

declare(strict_types=1);

namespace LunaPress\Cli\I18n\Pot\Scanner;

interface Scanner
{
    /**
     * @param string[] $files
     */
    public function scan(array $files, callable $callback): void;
}
