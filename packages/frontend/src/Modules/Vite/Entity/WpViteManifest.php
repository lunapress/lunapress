<?php
declare(strict_types=1);

namespace LunaPress\Frontend\Modules\Vite\Entity;

use LunaPress\FrontendContracts\Vite\IViteEntry;
use LunaPress\FrontendContracts\Vite\IViteManifest;
use RuntimeException;

defined('ABSPATH') || exit;

final readonly class WpViteManifest implements IViteManifest
{
    /** @var array<string, IViteEntry> */
    private array $entries;

    /**
     * @param array<string, array{
     *     file: string,
     *     isEntry?: bool,
     *     src?: string,
     *     css?: string[],
     * }> $manifestData
     */
    public function __construct(array $manifestData)
    {
        $entries = [];

        foreach ($manifestData as $name => $data) {
            if (!isset($data['file']) || !is_string($data['file'])) {
                throw new RuntimeException("Invalid or missing 'file' in Vite entry: {$name}");
            }

            $entries[$name] = new WpViteEntry(
                name: $name,
                file: $data['file'],
                isEntry: (bool) ($data['isEntry'] ?? false),
                css: is_array($data['css'] ?? null) ? $data['css'] : [],
                src: isset($data['src']) && is_string($data['src']) ? $data['src'] : null
            );
        }

        $this->entries = $entries;
    }

    /** @return IViteEntry[] */
    public function getEntries(): array
    {
        return array_values($this->entries);
    }

    public function hasEntry(string $name): bool
    {
        return isset($this->entries[$name]);
    }

    public function getEntry(string $name): ?IViteEntry
    {
        return $this->entries[$name] ?? null;
    }
}
