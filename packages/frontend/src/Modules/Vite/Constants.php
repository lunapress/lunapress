<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite;

use LunaPress\Wp\AssetsContracts\WpAssetHandle;

defined('ABSPATH') || exit;

final readonly class Constants
{
    public const HMR_HOST              = 'http://localhost:5173';
    public const MANIFEST_FILE_PATH    = '.vite/manifest.json';
    public const DEFAULT_FRONTEND_DEPS = [WpAssetHandle::COMPONENTS];
}
