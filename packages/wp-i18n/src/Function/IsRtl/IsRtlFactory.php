<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\IsRtl;

use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFactory;
use LunaPress\Wp\I18nContracts\Function\IsRtl\IIsRtlFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class IsRtlFactory implements IIsRtlFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IIsRtlFunction
    {
        return $this->container->get(IIsRtlFunction::class);
    }
}
