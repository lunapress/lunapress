<?php

declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\IsTextDomainLoaded;

use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFactory;
use LunaPress\Wp\I18nContracts\Function\IsTextDomainLoaded\IIsTextDomainLoadedFunction;
use Psr\Container\ContainerInterface;

final readonly class IsTextDomainLoadedFactory implements IIsTextDomainLoadedFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(string $domain): IIsTextDomainLoadedFunction
    {
        /** @var IIsTextDomainLoadedFunction $function */
        $function = $this->container->get(IIsTextDomainLoadedFunction::class);

        return $function->domain($domain);
    }
}
