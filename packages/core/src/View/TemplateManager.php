<?php
declare(strict_types=1);

namespace LunaPress\Core\View;

use InvalidArgumentException;
use LunaPress\FoundationContracts\View\ITemplateContextProvider;
use LunaPress\FoundationContracts\View\ITemplateManager;
use Override;
use RuntimeException;

defined('ABSPATH') || exit;

final class TemplateManager implements ITemplateManager
{
    private string $basePath = '';

    public function __construct(
        private readonly ITemplateContextProvider $globalContext,
    ) {
    }

    #[Override]
    public function setBasePath(string $path): ITemplateManager
    {
        $this->basePath = rtrim($path, '/');

        return $this;
    }

    #[Override]
    public function render(string $template, ITemplateContextProvider|array ...$contexts): void
    {
        echo $this->get($template, ...$contexts);
    }

    #[Override]
    public function get(string $template, ITemplateContextProvider|array ...$contexts): string
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

    private function mergeContexts(array ...$contexts): array
    {
        $resolved = array_map(
            static fn(array|ITemplateContextProvider $context): array =>
            match (true) {
                $context instanceof ITemplateContextProvider => $context->getContext(),
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
