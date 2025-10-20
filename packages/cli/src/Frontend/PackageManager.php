<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend;

defined('ABSPATH') || exit;

enum PackageManager: string
{
    case Pnpm = 'pnpm';
}
