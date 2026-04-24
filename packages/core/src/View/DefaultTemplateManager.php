<?php

declare(strict_types=1);

namespace LunaPress\Core\View;

use InvalidArgumentException;
use LunaPress\FoundationContracts\View\TemplateContextProvider;
use LunaPress\FoundationContracts\View\TemplateManager;
use Override;
use RuntimeException;
use function array_map;
use function array_replace_recursive;
use function extract;
use function get_debug_type;
use function is_array;
use function is_file;
use function ob_get_clean;
use function ob_start;
use function rtrim;
use function sprintf;
use function str_replace;
use function trim;
use const EXTR_SKIP;

final class DefaultTemplateManager implements TemplateManager
{
    private string $basePath = '';

    public function __construct(
        private readonly TemplateContextProvider $globalContext,
    ) {
    }

    #[Override]
    public function setBasePath(string $path): DefaultTemplateManager
    {
        $this->basePath = rtrim($path, '/');

        return $this;
    }

    #[Override]
    public function render(string $template, TemplateContextProvider|array ...$contexts): void
    {
        echo $this->get($template, ...$contexts);
    }

    #[Override]
    public function get(string $template, TemplateContextProvider|array ...$contexts): string
    {
        $template = trim($template, '/\\');
        $template = str_replace(['\\', '//'], '/', $template);

        $path = "{$this->basePath}/{$template}.php";
        if (!is_file($path)) {
            throw new RuntimeException("Template not found: {$path}");
        }

        $vars = $this->mergeContexts(
            $this->globalContext->getContext(),
            ...$contexts,
        );

        ob_start();
        extract($vars, EXTR_SKIP);
        include $path;
        return ob_get_clean() ?: '';
    }

    /**
     * @param  array<string, mixed> $contexts
     * @return array<string, mixed>
     */
    private function mergeContexts(array ...$contexts): array
    {
        $resolved = array_map(
            static fn(array|TemplateContextProvider $context): array =>
            match (true) {
                $context instanceof TemplateContextProvider => $context->getContext(),
                is_array($context) => $context,
                default => throw new InvalidArgumentException(
                    sprintf('Invalid context type: %s', get_debug_type($context))
                ),
            },
            $contexts,
        );

        return [...array_replace_recursive(...$resolved)];
    }
}
