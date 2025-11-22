<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Functions\LoadScriptTextDomain;

use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFactory;
use LunaPress\Wp\I18nContracts\Function\LoadScriptTextDomain\ILoadScriptTextDomainFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class LoadScriptTextDomainFactory implements ILoadScriptTextDomainFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $handle): ILoadScriptTextDomainFunction
    {
        /** @var ILoadScriptTextDomainFunction $function */
        $function = $this->container->get(ILoadScriptTextDomainFunction::class);

        return $function
            ->handle($handle);
    }
}
