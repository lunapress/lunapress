<?php
declare(strict_types=1);

namespace LunaPress\Core;

use LunaPress\CoreContracts\Support\HasDi;

defined('ABSPATH') || exit;

final readonly class DiProvider implements HasDi
{
    public static function getDiPath(): ?string
    {
        $base = dirname(__DIR__, 1);
        $path = $base . '/di.php';

        return file_exists($path) ? $path : null;
    }
}
