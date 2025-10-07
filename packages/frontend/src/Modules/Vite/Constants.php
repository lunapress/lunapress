<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite;

defined('ABSPATH') || exit;

final readonly class Constants
{
    public const HMR_HOST           = 'http://localhost:5173';
    public const MANIFEST_FILE_PATH = '.vite/manifest.json';
    public const WP_DEPS            = [ 'wp-i18n', 'wp-element', 'wp-html-entities', 'moment', 'lodash', 'wp-plugins' ];
    public const WC_DEPS            = [ 'wc-blocks-registry', 'wc-settings' ];
}
