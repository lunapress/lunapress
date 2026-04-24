<?php

declare(strict_types=1);

namespace LunaPress\Cli\Frontend\Init;

use LunaPress\Cli\Support\PathResolver;
use Override;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use function strtolower;

final readonly class DefaultFrontendProjectGenerator implements FrontendProjectGenerator
{
    public function __construct(
        private PathResolver $pathResolver,
        private Filesystem   $filesystem = new Filesystem()
    ) {
    }

    #[Override]
    public function generate(FrontendInitConfig $config): void
    {
        $targetPath   = $this->pathResolver->frontendInitPath($config);
        $framework    = strtolower($config->framework->value);
        $templatePath = $this->pathResolver->templates("frontend/$framework");

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
            ...$config->toTemplateArray(),
        ]);
    }
}
