<?php
declare(strict_types=1);

namespace LunaPress\Cli\Support;

defined('ABSPATH') || exit;

interface IWorkingDirectory
{
    public function current(): string;
}
