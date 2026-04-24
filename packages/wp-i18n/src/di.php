<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Wp\I18n\Service\Translator\DefaultTranslator;
use LunaPress\Wp\I18nContracts\Service\Translator\Translator;
use function LunaPress\Foundation\Container\autowire;

return [
    Translator::class => autowire(DefaultTranslator::class),
];
