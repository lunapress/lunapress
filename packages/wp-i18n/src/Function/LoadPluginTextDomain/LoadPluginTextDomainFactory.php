<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadPluginTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class LoadPluginTextDomainFactory implements ILoadPluginTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): ILoadPluginTextDomainFunction
    {
        /** @var ILoadPluginTextDomainFunction $function */
        $function = $this->container->get(ILoadPluginTextDomainFunction::class);

        return $function
            ->domain($domain);
    }
}
