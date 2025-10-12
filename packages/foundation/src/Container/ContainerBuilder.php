<?php
declare(strict_types=1);

namespace LunaPress\Foundation\Container;

use LunaPress\FoundationContracts\Container\IContainerBuilder;
use Psr\Container\ContainerInterface;
use RuntimeException;

defined('ABSPATH') || exit;

class ContainerBuilder implements IContainerBuilder
{
    /** @var array<string|array> */
    private array $definitions = [];

    public function addDefinitions(string|array $definitions): void
    {
        $this->definitions[] = $definitions;
    }

    public function build(): ContainerInterface
    {
        throw new RuntimeException(
            'Container building is not implemented in foundation. ' .
            'Use a concrete builder (e.g. in core).'
        );
    }

    /** @return array<string|array> */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }
}
