<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\GetAvailableLanguages;

use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFactory;
use LunaPress\Wp\I18nContracts\Function\GetAvailableLanguages\IGetAvailableLanguagesFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class GetAvailableLanguagesFactory implements IGetAvailableLanguagesFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IGetAvailableLanguagesFunction
    {
        return $this->container->get(IGetAvailableLanguagesFunction::class);
    }
}
