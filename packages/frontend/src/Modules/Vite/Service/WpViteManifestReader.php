<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\Frontend\Modules\Vite\Entity\WpViteManifest;
use LunaPress\FrontendContracts\Vite\IViteConfig;
use LunaPress\FrontendContracts\Vite\IViteEntry;
use LunaPress\FrontendContracts\Vite\IViteManifest;
use LunaPress\FrontendContracts\Vite\IViteManifestReader;
use RuntimeException;

defined('ABSPATH') || exit;

final readonly class WpViteManifestReader implements IViteManifestReader
{
    public function __construct(
        private IViteConfig $config
    ) {
    }

    public function getManifest(): IViteManifest
    {
        $filePath = rtrim($this->config->getBuildVitePath(), '/\\') . '/' . Constants::MANIFEST_FILE_PATH;

        if (!file_exists($filePath)) {
            throw new RuntimeException(sprintf('Vite manifest file not found: %s', $filePath));
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file read is safe
        $json = file_get_contents($filePath);
        if ($json === false) {
            throw new RuntimeException(sprintf('Failed to read Vite manifest file: %s', $filePath));
        }

        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON in Vite manifest: ' . json_last_error_msg());
        }

        return new WpViteManifest($data);
    }

    public function getEntry(string $name): ?IViteEntry
    {
        return $this->getManifest()->getEntry($name);
    }
}
