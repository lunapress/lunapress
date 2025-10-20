<?php
declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use LunaPress\Cli\Support\IPathResolver;
use LunaPress\Cli\Support\PathResolver;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

defined('ABSPATH') || exit;

final readonly class FrontendProjectGenerator implements IFrontendProjectGenerator
{
    public function __construct(
        private IPathResolver $pathResolver = new PathResolver(),
        private Filesystem $filesystem = new Filesystem()
    ) {
    }

    public function generate(FrontendInitConfig $config): void
    {
        $targetPath   = $this->pathResolver->frontendInitPath($config);
        $templatePath = $this->pathResolver->templates("frontend/{$config->framework->value}");

        $this->copyTemplate($templatePath, $targetPath);

        $this->templateRender($targetPath, $config);
    }

    private function copyTemplate(string $templatePath, string $targetPath): void
    {
        if ($this->filesystem->exists($targetPath)) {
            throw new RuntimeException("Frontend folder already exists: {$targetPath}");
        }

        if (!$this->filesystem->exists($templatePath)) {
            throw new RuntimeException("Template path not found: {$templatePath}");
        }

        $this->filesystem->mkdir($targetPath, 0755);
        $this->filesystem->mirror($templatePath, $targetPath);
    }

    private function templateRender(string $targetPath, FrontendInitConfig $config): void
    {
        $renderer = new FrontendTemplateRenderer($targetPath, $this->filesystem);

        $renderer->renderAll([
            ...$config->toArray(),
            'name' => $this->sanitizeName($config->directory),
        ]);
    }

    private function sanitizeName(string $name): string
    {
        return preg_replace('/[^a-z-]+/i', '', strtolower($name)) ?: '';
    }
}
