<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\FrontendContracts\Vite\ViteEnvDetector;
use function constant;
use function defined;

final class DefaultViteEnvDetector implements ViteEnvDetector
{
    public function isDev(): bool
    {
        return defined('VITE_DEV') && constant('VITE_DEV');
    }
}
