<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Wp\I18n\Service\Translator\Translator;
use LunaPress\Wp\I18nContracts\Service\Translator\ITranslator;
use function LunaPress\Foundation\Container\autowire;

return [
    ITranslator::class => autowire(Translator::class),
];
