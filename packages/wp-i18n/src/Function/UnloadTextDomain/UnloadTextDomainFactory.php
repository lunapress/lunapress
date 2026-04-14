<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\UnloadTextDomain;

use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\UnloadTextDomain\IUnloadTextDomainFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class UnloadTextDomainFactory implements IUnloadTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): IUnloadTextDomainFunction
    {
        /** @var IUnloadTextDomainFunction $function */
        $function = $this->container->get(IUnloadTextDomainFunction::class);

        return $function
            ->domain($domain);
    }
}
