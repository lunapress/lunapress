<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\Frontend\Modules\Vite\Constants;
use LunaPress\FrontendContracts\Vite\DTO\ViteConfig;
use LunaPress\FrontendContracts\Vite\ViteManifestFactory;
use LunaPress\FrontendContracts\Vite\ViteManifestReader;
use LunaPress\FrontendContracts\Vite\VO\ViteManifest;
use RuntimeException;
use function file_exists;
use function file_get_contents;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function rtrim;
use function sprintf;
use const JSON_ERROR_NONE;

final readonly class DefaultViteManifestReader implements ViteManifestReader
{
    public function __construct(
        private ViteConfig $config,
        private ViteManifestFactory $viteManifestFactory,
    ) {
    }

    public function getManifest(): ViteManifest
    {
        $filePath = rtrim($this->config->buildVitePath, '/\\') . '/' . Constants::MANIFEST_FILE_PATH;

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

        return $this->viteManifestFactory->make($data);
    }
}
