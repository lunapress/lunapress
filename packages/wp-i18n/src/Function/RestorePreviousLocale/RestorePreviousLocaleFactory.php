<?php
declare(strict_types=1);

namespace LunaPress\Wp\I18n\Function\RestorePreviousLocale;

use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFactory;
use LunaPress\Wp\I18nContracts\Function\RestorePreviousLocale\IRestorePreviousLocaleFunction;
use Psr\Container\ContainerInterface;

defined('ABSPATH') || exit;

final readonly class RestorePreviousLocaleFactory implements IRestorePreviousLocaleFactory
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function make(): IRestorePreviousLocaleFunction
    {
        return $this->container->get(IRestorePreviousLocaleFunction::class);
    }
}
