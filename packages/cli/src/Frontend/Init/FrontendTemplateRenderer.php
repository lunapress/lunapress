<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use Mustache\Engine;
use Mustache\Loader\FilesystemLoader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

defined('ABSPATH') || exit;

final readonly class FrontendTemplateRenderer
{
    private Engine $engine;
    private Filesystem $filesystem;

    public function __construct(private string $root, ?Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->engine     = new Engine([
            'loader' => new FilesystemLoader($this->root, ['extension' => '.mustache']),
        ]);
    }

    public function renderAll(array $context = []): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->root)
            ->name('*.mustache');

        foreach ($finder as $file) {
            $relativePath = Path::makeRelative($file->getPathname(), $this->root);

            $templateName = str_replace('.mustache', '', $relativePath);
            $targetPath   = Path::join($this->root, $templateName);

            $content = $this->engine->render($templateName, $context);
            $this->filesystem->dumpFile($targetPath, $content);
            $this->filesystem->remove($file->getPathname());
        }
    }
}
