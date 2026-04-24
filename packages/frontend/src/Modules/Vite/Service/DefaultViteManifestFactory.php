<?php

declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Service;

use LunaPress\FrontendContracts\Vite\ViteManifestFactory;
use LunaPress\FrontendContracts\Vite\VO\ViteManifest;
use LunaPress\FrontendContracts\Vite\VO\ViteManifestItem;
use RuntimeException;
use function is_array;
use function is_string;
use function str_ends_with;

class DefaultViteManifestFactory implements ViteManifestFactory
{
    /**
     * @inheritDoc
     */
    public function make(array $data): ViteManifest
    {
        $items = [];

        foreach ($data as $name => $chunk) {
            if (!isset($chunk['file']) || !is_string($chunk['file'])) {
                throw new RuntimeException("Invalid or missing 'file' in Vite entry: {$name}");
            }

            $file = $chunk['file'];
            $items[$name] = new ViteManifestItem(
                name: $name,
                file: $file,
                isCss: str_ends_with($file, '.css'),
                isEntry: (bool) ($chunk['isEntry'] ?? false),
                css: is_array($chunk['css'] ?? null) ? $chunk['css'] : [],
                src: isset($chunk['src']) && is_string($chunk['src']) ? $chunk['src'] : null
            );
        }

        return new ViteManifest($items);
    }
}
