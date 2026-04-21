<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\FrontendContracts\Vite\IViteModeDetector;
use function constant;
use function defined;

final class ConstantViteModeDetector implements IViteModeDetector
{
    public function isDev(): bool
    {
        return defined('VITE_DEV') && constant('VITE_DEV');
    }
}
