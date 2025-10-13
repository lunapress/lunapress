<?php
declare(strict_types=1);

namespace LunaPress\Core;

use LunaPress\FoundationContracts\Support\HasDi;

defined('ABSPATH') || exit;

final readonly class DiProvider implements HasDi
{
    public static function getDiPath(): ?string
    {
        $path = __DIR__ . '/di.php';

        return file_exists($path) ? $path : null;
    }
}
