<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite;

use LunaPress\Wp\AssetsContracts\WpAssetHandle;

defined('ABSPATH') || exit;

final readonly class Constants
{
    public const string HMR_HOST             = 'http://localhost:5173';
    public const string MANIFEST_FILE_PATH   = '.vite/manifest.json';
    public const array DEFAULT_FRONTEND_DEPS = [WpAssetHandle::COMPONENTS];
}
