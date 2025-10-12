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
use Psr\Container\ContainerInterface;
use function DI\autowire;
use function DI\factory;

defined('ABSPATH') || exit;

final class PhpDiContainerBuilder implements IContainerBuilder
{
    private ContainerBuilder $builder;

    public function __construct()
    {
        $this->builder = new ContainerBuilder();
    }

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
    public function build(): ContainerInterface
    {
        return $this->builder->build();
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
