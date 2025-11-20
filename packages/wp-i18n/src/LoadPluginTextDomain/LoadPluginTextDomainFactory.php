<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\LoadPluginTextDomain;

use LunaPress\Wp\I18nContracts\LoadPluginTextDomain\ILoadPluginTextDomainFactory;
use LunaPress\Wp\I18nContracts\LoadPluginTextDomain\ILoadPluginTextDomainFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
