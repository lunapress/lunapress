<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadMuPluginTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadMuPluginTextDomain\ILoadMuPluginTextDomainFunction;
use Psr\Container\ContainerInterface;

final readonly class LoadMuPluginTextDomainFactory implements ILoadMuPluginTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): ILoadMuPluginTextDomainFunction
    {
        /** @var ILoadMuPluginTextDomainFunction $function */
        $function = $this->container->get(ILoadMuPluginTextDomainFunction::class);

        return $function->domain($domain);
    }
}
