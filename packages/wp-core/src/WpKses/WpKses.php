<?php
declare(strict_types=1);

namespace LunaPress\Wp\Core\WpKses;

use LunaPress\Wp\CoreContracts\WpKses\IWpKsesFunction;

defined('ABSPATH') || exit;

final class WpKses implements IWpKsesFunction
{
    private string $content;
    private array|string $allowedHtml;
    private array $allowedProtocols = [];

    public function content(string $content): IWpKsesFunction
    {
        $this->content = $content;
        return $this;
    }

    public function allowedHtml(array|string $allowedHtml): IWpKsesFunction
    {
        $this->allowedHtml = $allowedHtml;
        return $this;
    }

    public function allowedProtocols(array $allowedProtocols): IWpKsesFunction
    {
        $this->allowedProtocols = $allowedProtocols;
        return $this;
    }

    /**
     * @return array{0: string, 1: array|string, 2: array}
     */
    public function rawArgs(): array
    {
        return [$this->content, $this->allowedHtml, $this->allowedProtocols];
    }

    /**
     * @param array{0: string, 1: array|string, 2: ?array} $args
     */
    public function executeWithArgs(array $args): string
    {
        return wp_kses(...$args);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAllowedHtml(): array|string
    {
        return $this->allowedHtml;
    }

    public function getAllowedProtocols(): array
    {
        return $this->allowedProtocols;
    }
}
