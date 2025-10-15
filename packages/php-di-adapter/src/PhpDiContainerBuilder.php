<?php
declare(strict_types=1);

namespace LunaPress\PhpDiAdapter;

use DI\ContainerBuilder;
use DI\Definition\Helper\AutowireDefinitionHelper;
use DI\Definition\Helper\FactoryDefinitionHelper;
use Exception;
use InvalidArgumentException;
use LunaPress\Foundation\Container\AutowireDefinition;
use LunaPress\Foundation\Container\FactoryDefinition;
use LunaPress\FoundationContracts\Container\IContainerBuilder;
use LunaPress\FoundationContracts\Container\IDefinition;
use Override;
use Psr\Container\ContainerInterface;
use function DI\autowire;
use function DI\factory;

defined('ABSPATH') || exit;

final class PhpDiContainerBuilder implements IContainerBuilder
{
    private ContainerBuilder $builder;
    private bool $cacheEnabled = true;
    private ?string $cachePath = null;

    public function __construct()
    {
        $this->builder = new ContainerBuilder();
    }

    #[Override]
    public function addDefinitions(string|array $definitions): void
    {
        // path
        if (is_string($definitions) && file_exists($definitions)) {
            $definitions = require $definitions;
        }

        if (is_array($definitions)) {
            foreach ($definitions as $key => $definition) {
                if ($definition instanceof IDefinition) {
                    $definitions[$key] = $this->convertDefinition($definition);
                }
            }
        }

        $this->builder->addDefinitions($definitions);
    }

    /**
     * @return ContainerInterface
     * @throws Exception
     */
    #[Override]
    public function build(): ContainerInterface
    {
        if ($this->isCacheEnabled() && !is_null($this->cachePath)) {
            $this->builder->enableCompilation($this->cachePath);
        }

        return $this->builder->build();
    }

    #[Override]
    public function enableCache(string $path): void
    {
        $this->cacheEnabled = true;
        $this->cachePath    = $path;
    }

    #[Override]
    public function disableCache(): void
    {
        $this->cacheEnabled = false;
    }

    #[Override]
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    private function convertDefinition(IDefinition $definition): AutowireDefinitionHelper|FactoryDefinitionHelper
    {
        return match (true) {
            $definition instanceof AutowireDefinition
            => autowire($definition->class),
            $definition instanceof FactoryDefinition
            => factory($definition->factory),
            default
            => throw new InvalidArgumentException(
                'Unsupported definition type: ' . $definition::class
            ),
        };
    }
}
