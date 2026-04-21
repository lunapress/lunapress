<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadTextDomain\ILoadTextDomainFunction;
use Psr\Container\ContainerInterface;

final readonly class LoadTextDomainFactory implements ILoadTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain, string $moFile): ILoadTextDomainFunction
    {
        /** @var ILoadTextDomainFunction $function */
        $function = $this->container->get(ILoadTextDomainFunction::class);

        return $function
            ->domain($domain)
            ->moFile($moFile);
    }
}
