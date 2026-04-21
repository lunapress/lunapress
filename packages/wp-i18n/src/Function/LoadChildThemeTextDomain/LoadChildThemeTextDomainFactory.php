<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadChildThemeTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadChildThemeTextDomain\ILoadChildThemeTextDomainFunction;
use Psr\Container\ContainerInterface;

final readonly class LoadChildThemeTextDomainFactory implements ILoadChildThemeTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): ILoadChildThemeTextDomainFunction
    {
        /** @var ILoadChildThemeTextDomainFunction $function */
        $function = $this->container->get(ILoadChildThemeTextDomainFunction::class);

        return $function->domain($domain);
    }
}
