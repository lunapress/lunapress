<?php
/**
 * @var IPluginTranslator $translator
 * @var ITranslateFactory $translateFactory
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use LunaPress\Cli\Test\Fixture\I18n\Pot\Generator\Case02_Frontend\IPluginTranslator;
use LunaPress\Wp\I18nContracts\Function\Translate\ITranslateFactory;

$translator->run(
    $translateFactory->make('PHP string')
);

// translators: 1) min amount, 2) max amount
__('From %1$s to %2$s', 'bred');
