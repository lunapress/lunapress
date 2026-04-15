<?php
declare(strict_types=1);

namespace LunaPress\Config;

defined('ABSPATH') || exit;

final class ProjectConfig
{
    /**
     * @var string[] $ignores
     */
    private array $ignores       = [];
    private array $straussConfig = [];

    public static function createDefault(): self
    {
        return new self();
    }

    /**
     * @param string[] $ignores
     * @return $this
     */
    public function withIgnores(array $ignores): self
    {
        $this->ignores = $ignores;
        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function withStrauss(array $config): self
    {
        $this->straussConfig = $config;
        return $this;
    }

    public function getIgnores(): array
    {
        return $this->ignores;
    }

    public function getStraussConfig(): array
    {
        return $this->straussConfig;
    }
}
