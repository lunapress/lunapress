<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\LoadThemeTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadThemeTextDomain\ILoadThemeTextDomainFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class LoadThemeTextDomainFactory implements ILoadThemeTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): ILoadThemeTextDomainFunction
    {
        /** @var ILoadThemeTextDomainFunction $function */
        $function = $this->container->get(ILoadThemeTextDomainFunction::class);

        return $function->domain($domain);
    }
}
